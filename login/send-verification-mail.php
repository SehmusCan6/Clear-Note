<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../PHPMailer/src/Exception.php';


function sendVerificationMail($toEmail, $toName, $link, $type = 'verify') {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'a80388616@gmail.com';
        $mail->Password   = 'cnih vdex urtj zhdt'; // App password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('a80388616@gmail.com', 'Clear Notes');
        $mail->addAddress($toEmail, $toName);
        $mail->isHTML(true);

        if ($type === 'verify') {
            $mail->Subject = 'Email Verification - Clear Notes';
            $mail->Body    = "
                Hello <b>$toName</b>,<br><br>
                Please click the link below to verify your email address and complete your registration:<br><br>
                <a href='$link'>$link</a><br><br>
                Thank you!<br>
                <b>Clear Notes Team</b>
            ";
        } else if ($type === 'reset') {
            $mail->Subject = 'Password Reset Request - Clear Notes';
            $mail->Body    = "
                Hello <b>$toName</b>,<br><br>
                We received a request to reset your password. Click the link below to set a new one:<br><br>
                <a href='$link'>$link</a><br><br>
                This link will expire in 1 hour.<br><br>
                If you didn’t request this, you can ignore this message.<br><br>
                <b>Clear Notes Team</b>
            ";
        }

        $mail->send();
        return true;

    } catch (Exception $e) {
        return "Mailer Error: " . $mail->ErrorInfo;
    }
}

