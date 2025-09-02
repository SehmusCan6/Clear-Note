<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);


if (isset($_SESSION['register_error'])) {
    echo "<pre style='color:red;'>🛑Registration Error (Mail): " . $_SESSION['register_error'] . "</pre>";
    unset($_SESSION['register_error']);
}


require('../connection/config.php');


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name'])) {
    $name     = trim($_POST["name"]);
    $surname  = trim($_POST["surname"]);
    $username = trim($_POST["username"]);
    $email    = trim($_POST["email"]);
    $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);


    $check_query = "SELECT id FROM users WHERE username = :username OR email = :email";
    $stmt = $pdo->prepare($check_query);
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $_SESSION['register_error'] = "Username or e-mail is already in use!";
        header("Location: ../index.php?register=fail");
        exit();
    } else {

        $token = bin2hex(random_bytes(16)); // 32 karakterlik doğrulama token'ı

        $insert_query = "INSERT INTO users (name, surname, username, email, password, verify_token)
                         VALUES (:name, :surname, :username, :email, :password, :token)";
        $stmt = $pdo->prepare($insert_query);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":surname", $surname);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":token", $token);

        if ($stmt->execute()) {

            require_once '../login/send-verification-mail.php';


            $verify_link = "http://localhost/clearnotesphp-main/login/verify.php?token=$token";
            $toName = "$name $surname";

            $result = sendVerificationMail($email, $toName, $verify_link);

            if ($result === true) {
                header("Location: ../index.php?register=success");
                exit();
            } else {
                $_SESSION['register_error'] = $result;
                header("Location: ../index.php?register=fail");
                exit();
            }
        } else {
            $_SESSION['register_error'] = "An error occurred during registration!";
            header("Location: ../index.php?register=fail");
            exit();
        }
    }
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_input'])) {
    $user_input = trim($_POST["user_input"]);
    $password   = trim($_POST["password"]);

    $query = "SELECT * FROM users WHERE username = :input OR email = :input";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":input", $user_input);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {

        if ($user["is_verified"] == 0) {
            $_SESSION['login_error'] = "Please verify your e-mail address first.";
            header("Location: ../index.php?login=fail");
            exit();
        }

        $_SESSION["user_id"]  = $user["id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["email"]    = $user["email"];
        $_SESSION["name"]     = $user["name"];
        $_SESSION["surname"]  = $user["surname"];

        header("Location: ../index.php?login=success");
        exit();

    } else {
        $_SESSION['login_error'] = "Username, e-mail or password are incorrect!";
        header("Location: ../index.php?login=fail");
        exit();
    }
}
?>
