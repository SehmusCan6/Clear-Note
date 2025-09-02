<?php
session_start();
require '../connection/config.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
$stmt->execute([$id]);

header("Location: tasks.php");
exit;
