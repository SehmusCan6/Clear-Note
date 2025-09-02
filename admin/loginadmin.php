<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Simple login check (replace with DB later)
    if ($username === "admin" && $password === "admin123") {
        $_SESSION["admin_logged_in"] = true;
        header("Location: ../admin/dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1e1e2f;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: "Segoe UI", sans-serif;
        }

        .login-box {
            background-color: #2c2c3e;
            padding: 30px;
            border-radius: 10px;
            width: 350px;
        }

        .btn-orange {
            background-color: #ff6f00;
            border: none;
            color: white;
        }

        .btn-orange:hover {
            background-color: #e65c00;
        }

        .back-link {
            color: #ffc107;
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .error {
            color: #ff4d4d;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="login-box text-center">
    <h3 class="mb-4">🔐 Admin Login</h3>
    <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
    <form action="loginadmin.php" method="POST">
        <input type="text" class="form-control mb-3" name="username" placeholder="Username" required>
        <input type="password" class="form-control mb-3" name="password" placeholder="Password" required>
        <button type="submit" class="btn btn-orange w-100">Login</button>
        <a href="../index.php" class="back-link">← Back to Homepage</a>
    </form>
</div>
</body>
</html>
