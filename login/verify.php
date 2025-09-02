<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require('../connection/config.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];


    $stmt = $pdo->prepare("SELECT * FROM users WHERE verify_token = :token LIMIT 1");
    $stmt->bindParam(":token", $token);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {
        $update = $pdo->prepare("UPDATE users SET is_verified = 1, verify_token = NULL WHERE verify_token = :token");
        $update->bindParam(":token", $token);
        $update->execute();

        $success = true;
    } else {
        $success = false;
    }
} else {
    $success = false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>E-Mail Verification</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<script>
    <?php if ($success): ?>
    Swal.fire({
        icon: 'success',
        title: 'Verification Successful 🎉',
        text: 'You Can Log in right now',
        confirmButtonText: 'Ok'
    }).then(() => {
        window.location.href = '../index.php';
    });
    <?php else: ?>
    Swal.fire({
        icon: 'error',
        title: 'Verification Unsuccessful',
        text: 'The token may be invalid or already verified.',
        confirmButtonText: 'Home Page'
    }).then(() => {
        window.location.href = '../index.php';
    });
    <?php endif; ?>
</script>
</body>
</html>
