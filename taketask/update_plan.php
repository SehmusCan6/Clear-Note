<?php
session_start();
require '../connection/config.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION["user_id"] ?? null;
    $plan_id = $_POST["plan_id"] ?? null;
    $title = trim($_POST["title"] ?? '');
    $subject = trim($_POST["subject"] ?? '');
    $description = trim($_POST["description"] ?? '');
    $date = trim($_POST["date"] ?? '');

    if ($user_id && $plan_id && $title && $date) {
        $stmt = $pdo->prepare("UPDATE tasks SET title = ?, subject = ?, description = ?, date = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$title, $subject, $description, $date, $plan_id, $user_id]);

        echo json_encode([
            "success" => true,
            "id" => $plan_id,
            "title" => $title,
            "subject" => $subject,
            "description" => $description,
            "date" => $date
        ]);
        exit();
    } else {
        echo json_encode(["success" => false, "message" => "Missing or invalid data"]);
        exit();
    }
}

