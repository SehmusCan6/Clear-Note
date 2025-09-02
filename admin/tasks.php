<?php
session_start();
require '../connection/config.php';

if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: loginadmin.php");
    exit;
}


$stmt = $pdo->prepare("
    SELECT tasks.*, users.username 
    FROM tasks
    INNER JOIN users ON tasks.user_id = users.id
    WHERE users.can_tasks = 1
    ORDER BY tasks.date DESC
");
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tasks</title>
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
    <h2>📅 Tasks</h2>
    <a href="tasks_add.php" class="btn btn-success mb-3">➕ Add Task</a>
    <table class="table table-dark table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Date</th>
            <th>Title</th>
            <th>Subject</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($tasks as $task): ?>
            <tr>
                <td><?= $task['id'] ?></td>
                <td><?= $task['user_id'] ?></td>
                <td><?= $task['date'] ?></td>
                <td><?= $task['title'] ?></td>
                <td><?= $task['subject'] ?></td>
                <td><?= $task['description'] ?></td>
                <td>
                    <a href="tasks_edit.php?id=<?= $task['id'] ?>" class="btn btn-warning btn-sm">✏️</a>
                    <a href="tasks_delete.php?id=<?= $task['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this task?')">🗑️</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
