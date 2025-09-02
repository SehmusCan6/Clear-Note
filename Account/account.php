<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../connection/config.php';

// Giriş yapmamışsa yönlendir
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Bilgi güncelleme isteği varsa
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $surname = trim($_POST["surname"]);
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);

    // Şifre güncellenmek isteniyorsa
    $newPassword = !empty($_POST["password"]) ? password_hash($_POST["password"], PASSWORD_DEFAULT) : null;


    $can_notes = isset($_POST['can_notes']) ? 1 : 0;
    $can_tasks = isset($_POST['can_tasks']) ? 1 : 0;

    $query = "UPDATE users SET name = :name, surname = :surname, username = :username, email = :email,
          can_notes = :can_notes, can_tasks = :can_tasks";
    if ($newPassword) {
        $query .= ", password = :password";

    }
    $query .= " WHERE id = :id";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":surname", $surname);
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":can_notes", $can_notes);
    $stmt->bindParam(":can_tasks", $can_tasks);
    if ($newPassword) {
        $stmt->bindParam(":password", $newPassword);
    }
    $stmt->bindParam(":id", $user_id);

    if ($stmt->execute()) {

        $_SESSION['name'] = $name;
        $_SESSION['surname'] = $surname;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['can_notes'] = $can_notes;
        $_SESSION['can_tasks'] = $can_tasks;

        if ($newPassword) {
            $_SESSION['password'] = $newPassword;
        }

        $success = "Information has been successfully updated!";
    } else {
        $error = "The update failed.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Organise Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    body {
        background-color: #121212;
        color: #e0e0e0;
        font-family: 'Segoe UI', sans-serif;
    }

    .container {
        max-width: 600px;
        background-color: #1f1f1f;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6);
    }

    h2 {
        color: #fca311;
        font-weight: bold;
        margin-bottom: 30px;
        text-align: center;
    }

    label {
        color: #ccc;
        font-weight: 500;
    }

    input.form-control {
        background-color: #2a2a2a;
        color: #fff;
        border: 1px solid #444;
        border-radius: 6px;
    }

    input.form-control:focus {
        border-color: #fca311;
        box-shadow: 0 0 0 0.2rem rgba(252, 163, 17, 0.25);
    }

    .btn-primary {
        background-color: #fca311;
        border: none;
        font-weight: bold;
    }

    .btn-primary:hover {
        background-color: #ffb347;
        color: #121212;
    }

    .btn-outline-light {
        border-color: #fca311;
        color: #fca311;
    }

    .btn-outline-light:hover {
        background-color: #fca311;
        color: #121212;
    }

    .alert-success, .alert-danger {
        border-radius: 6px;
        font-weight: 500;
        padding: 12px 16px;
    }

    .form-check-input {
        background-color: #2a2a2a;
        border: 1px solid #444;
        width: 45px;
        height: 22px;
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: #fca311;
        border-color: #fca311;
    }

    .form-check-label {
        color: #ccc;
        margin-left: 10px;
        font-weight: 500;
    }
</style>

<body class="bg-dark text-white">
<div class="container mt-5">
    <h2>Organise Profile Information</h2>

    <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($_SESSION['name']); ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Surname</label>
            <input type="text" name="surname" value="<?php echo htmlspecialchars($_SESSION['surname']); ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>User Name</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>E-Mail</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>New Password (It won't change if it's left blank)</label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" name="can_notes" id="can_notes"
                <?= !isset($_SESSION['can_notes']) || $_SESSION['can_notes'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="can_notes">Can access notes?</label>
        </div>

        <div class="form-check form-switch mb-4">
            <input class="form-check-input" type="checkbox" name="can_tasks" id="can_tasks"
                <?= !isset($_SESSION['can_tasks']) || $_SESSION['can_tasks'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="can_tasks">Can access tasks?</label>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
    <br>
    <a href="../index.php" class="btn btn-outline-light mb-3">🏠 Home Page</a>
</div>
</body>
</html>
