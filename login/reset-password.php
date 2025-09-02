<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Set New Password</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        form {
            background-color: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 12px 24px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        form h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        form input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            transition: border 0.3s;
        }

        form input[type="password"]:focus {
            outline: none;
            border-color: #fca311;
            box-shadow: 0 0 5px rgba(252, 163, 17, 0.3);
        }

        form button {
            width: 100%;
            background-color: #fca311;
            border: none;
            color: #fff;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }

        form button:hover {
            background-color: #ffb347;
            transform: translateY(-1px);
        }

        .message {
            text-align: center;
            font-size: 14px;
            margin-top: 20px;
            color: red;
        }
    </style>
</head>
<body>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require('../connection/config.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = :token AND reset_expires > NOW()");
    $stmt->bindParam(":token", $token);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_password'])) {
            $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("UPDATE users SET password = :password, reset_token = NULL, reset_expires = NULL WHERE id = :id");
            $stmt->execute([
                ":password" => $newPassword,
                ":id" => $user['id']
            ]);

            echo "<script>alert('Your password has been successfully updated. You can now log in.'); window.location.href='http://localhost/clearnotesphp-main/index.php';</script>";
            exit();
        }
        ?>
        <form method="POST">
            <h2>Set New Password</h2>
            <input type="password" name="new_password" placeholder="New password" required>
            <button type="submit">Save</button>
        </form>
        <?php
    } else {
        echo "<div class='message'>The token is invalid or has expired.</div>";
    }
} else {
    echo "<div class='message'>No token was found.</div>";
}
?>
</body>
</html>
