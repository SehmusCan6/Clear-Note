<?php
session_start();
require '../connection/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO users (name, surname, username, email, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['name'],
        $_POST['surname'],
        $_POST['username'],
        $_POST['email'],
        password_hash($_POST['password'], PASSWORD_DEFAULT)
    ]);
    header('Location: users.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yeni Kullanıcı</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1e1e2f;
            font-family: "Segoe UI", sans-serif;
            color: #fff;
        }

        .form-box {
            max-width: 500px;
            margin: 50px auto;
            background-color: #2c2c3e;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(255, 111, 0, 0.2);
        }

        .form-box h3 {
            color: #ff6f00;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-control {
            background-color: #1e1e2f;
            border: 1px solid #444;
            color: #fff;
            border-radius: 8px;
            padding: 10px 15px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #ff6f00;
            box-shadow: none;
            background-color: #26263a;
        }

        .btn-orange {
            background-color: #ff6f00;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 8px;
            width: 100%;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn-orange:hover {
            background-color: #e65c00;
        }

        .back-btn {
            margin-bottom: 20px;
        }
        ::placeholder {
            color: #cccccc !important;
            opacity: 1;
        }

        .back-btn {
            display: block;
            width: max-content;
            margin: 20px auto 0;
            color: #ffc107;
            text-decoration: none;
        }
    </style>
</head>
<body>

<a href="dashboard.php" class="back-btn">⬅️ Dashboard'a Dön</a>

<div class="container">
    <div class="form-box">
        <h3>New User Add</h3>
        <form method="POST">
            <input name="name" class="form-control mb-3"  placeholder="Name" required>
            <input name="surname" class="form-control mb-3" placeholder="Surname" required>
            <input name="username" class="form-control mb-3" placeholder="User Name" required>
            <input name="email" type="email" class="form-control mb-3" placeholder="Email" required>
            <input type="password" name="password" class="form-control mb-4" placeholder="Password" required>
            <button type="submit" class="btn-orange">✅ Save</button>
        </form>
    </div>
</div>

</body>
</html>
