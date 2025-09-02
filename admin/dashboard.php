<?php
session_start();
if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: loginadmin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1e1e2f;
        }
        .sidebar {
            min-height: 100vh;
            width: 220px;
        }
        .list-group-item {
            border: none;
        }
        .list-group-item:hover {
            background-color: #ff6f00 !important;
            color: white !important;
        }
    </style>
</head>
<body>

<div class="d-flex" id="wrapper">

    <!-- Sidebar -->
    <div class="bg-dark text-white sidebar" id="sidebar-wrapper">
        <div class="sidebar-heading px-3 py-4" style="background-color:#ff6f00;">🧡 Admin Panel</div>
        <div class="list-group list-group-flush">
            <a href="users.php" class="list-group-item list-group-item-action bg-dark text-white">👤 Users</a>
            <a href="anote.php" class="list-group-item list-group-item-action bg-dark text-white">🗒️ Notes</a>
            <a href="tasks.php" class="list-group-item list-group-item-action bg-dark text-white">📅 Tasks</a>
            <a href="contacts.php" class="list-group-item list-group-item-action bg-dark text-white">📬 Contact Messages</a>
            <a href="logoutadmin.php" class="list-group-item list-group-item-action bg-dark text-white">🚪 Logout</a>
        </div>
    </div>

    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100">
        <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #2c2c3e;">
            <div class="container-fluid">
                <span class="navbar-brand">Admin Dashboard</span>
            </div>
        </nav>
        <div class="container-fluid mt-4 text-white">
            <h2>Welcome, Admin!</h2>
            <p>You can manage users, notes, tasks and messages from this panel.</p>
        </div>
    </div>

</div>

</body>
</html>
