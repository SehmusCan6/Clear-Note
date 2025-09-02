<?php
session_start();
require '../connection/config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE users SET name=?,surname=?,username=?, email=? WHERE id=?");
    $stmt->execute([
        $_POST['name'],
        $_POST['surname'],
        $_POST['username'],
        $_POST['email'],

        $id
    ]);
    header("Location: users.php"); // Kullanıcı listesine geri dön
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1e1e2f;
            font-family: "Segoe UI", sans-serif;
            color: white;
        }

        .form-box {
            max-width: 550px;
            margin: 50px auto;
            background-color: #2c2c3e;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(255, 111, 0, 0.2);
        }

        .form-box h3 {
            color: #ff6f00;
            text-align: center;
            margin-bottom: 25px;
        }

        .form-control {
            background-color: #1e1e2f;
            border: 1px solid #444;
            color: white;
            border-radius: 8px;
            padding: 10px 15px;
        }

        .form-control:focus {
            color: #ffffff;
            background-color: #26263a;
            border-color: #ff6f00;
            box-shadow: none;
        }


        .form-control::placeholder {
            color: #ccc !important;
            opacity: 1;
        }

        .btn-orange {
            background-color: #ff6f00;
            color: #fff;
            border: none;
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            font-weight: bold;
        }

        .btn-orange:hover {
            background-color: #e65c00;
            color: #fff;
        }

        .back-btn {
            display: block;
            width: max-content;
            margin: 20px auto 0;
            color: #ffc107;
            text-decoration: none;
        }

        .back-btn:hover {
            text-decoration: underline;
            color: #fff;
        }
    </style>
</head>
<body>

<a href="dashboard.php" class="back-btn">⬅️ Back to Dashboard</a>

<div class="form-box">
    <h3>Edit User</h3>
    <form method="POST">
        <input name="name" class="form-control mb-3" value="<?= htmlspecialchars($user['name']) ?>" placeholder="Name" required>
        <input name="surname" class="form-control mb-3" value="<?= htmlspecialchars($user['surname']) ?>" placeholder="Surname" required>
        <input name="username" class="form-control mb-3" value="<?= htmlspecialchars($user['username']) ?>" placeholder="User Name" required>
        <input type="email" name="email" class="form-control mb-3" value="<?= htmlspecialchars($user['email']) ?>" placeholder="Email" required>
        <button type="submit" class="btn-orange">✅ Update</button>
    </form>
</div>

</body>
</html>
