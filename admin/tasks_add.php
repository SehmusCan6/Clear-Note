<?php
session_start();
require '../connection/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO tasks (user_id, date, title, subject, description, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->execute([
        $_POST['user_id'],
        $_POST['date'],
        $_POST['title'],
        $_POST['subject'],
        $_POST['description']
    ]);
    header("Location: tasks.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Task</title>
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
            border-color: #ff6f00;
            box-shadow: none;
            background-color: #26263a;
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
    </style>
</head>
<body>

<a href="dashboard.php" class="back-btn">⬅️ Back to Dashboard</a>

<div class="form-box">
    <h3>Add New Task</h3>
    <form method="POST">
        <input name="user_id" class="form-control mb-3" placeholder="User ID" required>
        <input type="date" name="date" class="form-control mb-3" required>
        <input name="title" class="form-control mb-3" placeholder="Title" required>
        <input name="subject" class="form-control mb-3" placeholder="Subject">
        <textarea name="description" class="form-control mb-4" placeholder="Description" rows="4"></textarea>
        <button type="submit" class="btn-orange">✅ Save</button>
    </form>
</div>

</body>
</html>
