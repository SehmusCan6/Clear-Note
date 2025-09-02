<?php
session_start();
require '../connection/config.php';

if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: loginadmin.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1e1e2f;
            color: white;
            font-family: "Segoe UI", sans-serif;
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
        }

        h2 {
            color: #ff6f00;
        }

        .btn-success {
            background-color: #ff6f00;
            border: none;
        }

        .btn-success:hover {
            background-color: #e65c00;
        }
    </style>
</head>
<body>

<a href="dashboard.php" class="back-btn">⬅️ Back to Dashboard</a>

<div class="container mt-5">
    <h2 class="mb-4">👥 User List</h2>
    <a href="user_add.php" class="btn btn-success mb-3">➕ Add New User</a>
    <table class="table table-dark table-striped table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= $user['name'] ?></td>
                <td><?= $user['surname'] ?></td>
                <td><?= $user['username'] ?></td>
                <td><?= $user['email'] ?></td>
                <td>
                    <a href="user_edit.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-warning">✏️ Edit</a>
                    <a href="user_delete.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">🗑️ Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
