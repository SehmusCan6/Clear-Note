<?php
session_start();
require '../connection/config.php';

$stmt = $pdo->query("SELECT * FROM contact_new_user ORDER BY created_at DESC");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Messages</title>
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

        h2 {
            color: #ff6f00;
        }
    </style>
</head>
<body>

<a href="dashboard.php" class="back-btn">⬅️ Back to Dashboard</a>

<div class="container mt-5">
    <h2>📬 User Contact Messages</h2>
    <table class="table table-dark table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($messages as $msg): ?>
            <tr>
                <td><?= $msg['id'] ?></td>
                <td><?= $msg['name'] ?></td>
                <td><?= $msg['email'] ?></td>
                <td><?= $msg['about'] ?></td>
                <td><?= $msg['created_at'] ?></td>
                <td>
                    <a href="contacts_delete.php?id=<?= $msg['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this message?')">🗑️ Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
