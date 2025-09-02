<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require '../connection/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo "Not Log in.";
    exit;
}

$username = $_SESSION['username'];

$stmt = $pdo->prepare("SELECT email, name, surname FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User Not Found";
    exit;
}

// Form verileri
$title = $_POST['title'] ?? '';
$subjectPlan = $_POST['subject'] ?? '';
$description = $_POST['description'] ?? '';
$date = $_POST['date'] ?? '';

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // SMTP Ayarları
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = '#'; #email
    $mail->Password = '#'; #smtp password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Mail content
    $mail->setFrom('a80388616@gmail.com', 'Plan Reminder');
    $mail->addAddress($user['email'], $user['name'] . ' ' . $user['surname']);

    $mail->isHTML(true);
    $mail->Subject = "Plan Reminder: $title";
    $mail->Body = "
    <div style='font-family: Arial, sans-serif; font-size: 15px;'>
        Hello <strong>{$user['name']} {$user['surname']}</strong>,<br><br>
        You have a scheduled plan with the following details:<br><br>
        <strong>Date:</strong> $date<br>
        <strong>Title:</strong> $title<br>
        <strong>Subject:</strong> $subjectPlan<br><br>
        <strong>Description:</strong><br>
        <div style='border-left: 3px solid #fca311; padding-left: 10px;'>$description</div><br>
        We wish you a productive day!<br><br>
        — Clear Notes Team
    </div>
";


    $mail->send();
    echo "✅ The mail has been sent successfully!";
} catch (Exception $e) {
    echo "❌ Mail could not be sent. Error: {$mail->ErrorInfo}";
}
?>
