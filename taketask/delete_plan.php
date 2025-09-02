<?php
session_start();
require '../connection/config.php';

header('Content-Type: application/json');

$id = $_POST["id"] ?? null;
$user_id = $_SESSION["user_id"] ?? null;

if ($id && $user_id) {
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);

    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Eksik veya geçersiz veri"]);
}
