<?php
session_start();
require '../connection/config.php';

if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: loginadmin.php");
    exit;
}


$stmt = $pdo->prepare("
    SELECT notes.*, users.username 
    FROM notes
    INNER JOIN users ON notes.user_id = users.id
    WHERE users.can_notes = 1
    ORDER BY notes.created_at DESC
");
$stmt->execute();
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Notes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #1e1e2f; color: white; font-family: "Segoe UI", sans-serif; }
        .back-btn { display: block; width: max-content; margin: 20px auto 0; color: #ffc107; text-decoration: none; }
        .back-btn:hover { text-decoration: underline; }
        h2 { color: #ff6f00; }
        .btn-success { background-color: #ff6f00; border: none; }
        .btn-success:hover { background-color: #e65c00; }
    </style>
</head>
<body>

<a href="dashboard.php" class="back-btn">⬅️ Back to Dashboard</a>

<div class="container mt-5">
    <h2>🗒️ All Notes (Admin)</h2>
    <a href="note_add.php" class="btn btn-success mb-3">➕ Add Note</a>

    <table class="table table-dark table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Title</th>
            <th>Content</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($notes as $note): ?>
            <tr>
                <td><?= $note['id'] ?></td>
                <td><?= htmlspecialchars($note['username']) ?></td>
                <td><?= htmlspecialchars($note['title']) ?></td>
                <td><?= htmlspecialchars($note['content']) ?></td>
                <td>
                    <a href="note_edit.php?id=<?= $note['id'] ?>" class="btn btn-warning btn-sm">✏️</a>
                    <a href="note_delete.php?id=<?= $note['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete?')">🗑️</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
