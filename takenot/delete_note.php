<?php
session_start();
require '../connection/config.php';
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    $user_id = $_SESSION['user_id'] ?? null;

    if ($user_id && $id) {
        $stmt = $pdo->prepare("DELETE FROM notes WHERE id = :id AND user_id = :user_id");
        $stmt->execute([
            'id' => $id,
            'user_id' => $user_id
        ]);

        echo json_encode(['success' => true]);
        exit;
    }
}

echo json_encode(['success' => false]);
