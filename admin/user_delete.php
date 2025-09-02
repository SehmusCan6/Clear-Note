<?php
session_start();
require '../connection/config.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$id]);

header("Location: users.php");
exit;

