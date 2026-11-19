<?php
session_start();

require_once "../includes/config.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

// Marrja e statistikave nga databaza
$total_cars = $conn->query("SELECT COUNT(*) FROM cars")->fetchColumn();

$total_users = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();

$total_bookings = $conn->query("SELECT COUNT(*) FROM bookings")->fetchColumn();

$pending_bookings = $conn->query(
    "SELECT COUNT(*) FROM bookings WHERE status = 'pending'"
)->fetchColumn();

$full_name = htmlspecialchars($_SESSION["full_name"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CarRent</title>

    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="admin-body">

<div class="admin-layout">

    <!-- SIDEBAR -->
    <aside class="admin-sidebar">

        <h2 class="admin-logo">CAR<span>RENT</span></h2>

        <p class="sidebar-title">MAIN MENU</p>

        <a href="dashboard.php" class="sidebar-link active">
            Dashboard
        </a>

        <a href="cars.php" class="sidebar-link">
            Manage Cars
        </a>

        <a href="bookings.php" class="sidebar-link">
            Manage Bookings
        </a>

        <a href="users.php" class="sidebar-link">
            Manage Users
        </a>

        <div class="sidebar-bottom">
            <a href="../index.php" class="sidebar-link">
                Back to Website
            </a>
        </div>

    </aside>

    <!-- MAIN CONTENT -->
    <main class="admin-main">

        <div class="admin-topbar">
            <div>
                <h1>Dashboard</h1>
                <p>Welcome to CarRent administration panel.</p>
            </div>

            <div class="admin-profile">
                <div class="profile-circle">A</div>
                <span><?php echo $full_name; ?></span>
            </div>
        </div>

        <section class="welcome-card">
            <div>
                <h2>Welcome back, <?php echo $full_name; ?>!</h2>
                <p>Manage your cars, users and bookings from here.</p>
            </div>
            <div class="welcome-icon">🚘</div>
        </section>

        <h2 class="section-heading">Overview</h2>

        <section class="stats-grid">

            <div class="stat-card">
                <div class="stat-icon">🚗</div>
                <p>Total Cars</p>
                <h2><?php echo $total_cars; ?></h2>
            </div>

            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <p>Total Users</p>
                <h2><?php echo $total_users; ?></h2>
            </div>

            <div class="stat-card">
                <div class="stat-icon">📅</div>
                <p>Total Bookings</p>
                <h2><?php echo $total_bookings; ?></h2>
            </div>

            <div class="stat-card">
                <div class="stat-icon">⏳</div>
                <p>Pending Bookings</p>
                <h2><?php echo $pending_bookings; ?></h2>
            </div>

        </section>

        <section class="admin-info">
            <h2>System Information</h2>
            <p>
                Your CarRent administration panel is working successfully.
                Use the sidebar to manage the rental system.
            </p>
        </section>

    </main>

</div>

</body>
</html>