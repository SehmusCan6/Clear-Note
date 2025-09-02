<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require('../connection/config.php');
require_once '../login/send-verification-mail.php'; // PHPMailer fonksiyonu buraya dahil

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
    $email = trim($_POST['email']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $stmt = $pdo->prepare("UPDATE users SET reset_token = :token, reset_expires = :expires WHERE email = :email");
        $stmt->execute([
            ":token" => $token,
            ":expires" => $expires,
            ":email" => $email
        ]);

        // Reset link
        $reset_link = "http://localhost/clearnotesphp-main/login/reset-password.php?token=$token";
        $toName = "Clear Notes User";

        // ✅ BURASI ÖNEMLİ: 'reset' olarak 4. parametre geçiliyor
        $result = sendVerificationMail($email, $toName, $reset_link, 'reset');

        if ($result === true) {
            $success = "✅ A password reset link has been sent to your email.";
        } else {
            $error = "❌ Mail error: " . $result;
        }
    } else {
        $error = "⚠️ No user found with that email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - Clear Notes</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f0f0;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 12px 24px rgba(0,0,0,0.1);
            max-width: 400px;
            width: 100%;
        }

        form h2 {
            margin-bottom: 25px;
            text-align: center;
            color: #333;
        }

        form input[type="email"] {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            margin-bottom: 20px;
            transition: 0.3s;
        }

        form input[type="email"]:focus {
            border-color: #fca311;
            outline: none;
            box-shadow: 0 0 6px rgba(252, 163, 17, 0.3);
        }

        form button {
            width: 100%;
            background-color: #fca311;
            color: #fff;
            padding: 12px;
            border: none;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }

        form button:hover {
            background-color: #ffb347;
            transform: translateY(-1px);
        }

        .message {
            font-size: 14px;
            margin-bottom: 15px;
            text-align: center;
        }

        .message.success {
            color: green;
        }

        .message.error {
            color: red;
        }
    </style>
</head>
<body>

<form method="POST">
    <h2>Forgot Password</h2>

    <?php if (!empty($success)) echo "<div class='message success'>$success</div>"; ?>
    <?php if (!empty($error)) echo "<div class='message error'>$error</div>"; ?>

    <input type="email" name="email" placeholder="Enter your registered email" required>
    <button type="submit">Send Reset Link</button>
</form>

</body>
</html>
