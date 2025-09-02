<?php
session_start();
require '../connection/config.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"] ?? '');
    $content = trim($_POST["content"] ?? '');
    $user_id = $_SESSION["user_id"] ?? null;

    if (!empty($title) && !empty($content) && !empty($user_id)) {
        $stmt = $pdo->prepare("INSERT INTO notes (user_id, title, content) VALUES (:user_id, :title, :content)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->execute();

        $created_at = date("Y-m-d H:i:s");

        echo json_encode([
            'success' => true,
            'id' => $pdo->lastInsertId(),
            'title' => htmlspecialchars($title),
            'content' => nl2br(htmlspecialchars($content)),
            'created_at' => date("Y-m-d H:i:s")
        ]);

        exit;
    }

}

echo json_encode(["success" => false]);
exit;
