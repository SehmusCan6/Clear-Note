<?php
session_start();
require '../connection/config.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM contact_new_user WHERE id = ?");
$stmt->execute([$id]);

header("Location: contacts.php");
exit;
