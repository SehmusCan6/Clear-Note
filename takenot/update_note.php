<?php
session_start();
require '../connection/config.php';
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'] ?? null;
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $user_id = $_SESSION['user_id'] ?? null;

    if ($id && $title && $content && $user_id) {
        $stmt = $pdo->prepare("UPDATE notes SET title = :title, content = :content WHERE id = :id AND user_id = :user_id");
        $stmt->execute([
            'title' => $title,
            'content' => $content,
            'id' => $id,
            'user_id' => $user_id
        ]);

        echo json_encode([
            'success' => true,
            'title' => htmlspecialchars($title),
            'content' => nl2br(htmlspecialchars($content)),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        exit;
    }
}

echo json_encode(['success' => false]);
