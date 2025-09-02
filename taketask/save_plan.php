<?php
session_start();
require '../connection/config.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION["user_id"] ?? null;
    $title = trim($_POST["title"] ?? '');
    $subject = trim($_POST["subject"] ?? '');
    $description = trim($_POST["description"] ?? '');
    $date = trim($_POST["date"] ?? '');

    if ($user_id && $title && $date) {
        $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, subject, description, date) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $title, $subject, $description, $date]);

        $plan_id = $pdo->lastInsertId();

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
        echo json_encode(["success" => false, "message" => "Eksik veri"]);
        exit();
    }
}
