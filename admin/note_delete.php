<?php
session_start();
require '../connection/config.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM notes WHERE id = ?");
$stmt->execute([$id]);

header("Location: anote.php");
exit;
