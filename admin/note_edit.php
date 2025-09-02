<?php
session_start();
require '../connection/config.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM notes WHERE id = ?");
$stmt->execute([$id]);
$note = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE notes SET user_id = ?, title = ?, content = ? WHERE id = ?");
    $stmt->execute([
        $_POST['user_id'],
        $_POST['title'],
        $_POST['content'],
        $id
    ]);
    header("Location: anote.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Note</title>
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
            color: #ffffff; /* Tıklanınca yazı beyaz */
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

<a href="dashboard.php" class="back-btn">⬅️ Back to Dashboard</a>

<div class="form-box">
    <h3>Edit Note</h3>
    <form method="POST">
        <input name="user_id" class="form-control mb-3" value="<?= $note['user_id'] ?>" placeholder="User ID" required>
        <input name="title" class="form-control mb-3" value="<?= $note['title'] ?>" placeholder="Title" required>
        <textarea name="content" class="form-control mb-4" placeholder="Content" rows="5" required><?= $note['content'] ?></textarea>
        <button type="submit" class="btn-orange">✅ Update</button>
    </form>
</div>

</body>
</html>
