
<?php
session_start();

require_once "../includes/config.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

// STATISTIKAT

$total_cars = $conn->query("SELECT COUNT(*) FROM cars")->fetchColumn();

$available_cars = $conn->query("
    SELECT COUNT(*) FROM cars WHERE status = 'available'
")->fetchColumn();

$total_users = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();

$total_bookings = $conn->query("SELECT COUNT(*) FROM bookings")->fetchColumn();

$pending_bookings = $conn->query("
    SELECT COUNT(*) FROM bookings WHERE status = 'pending'
")->fetchColumn();

// 5 REZERVIMET E FUNDIT

$stmt = $conn->query("
    SELECT
        bookings.id,
        bookings.start_date,
        bookings.end_date,
        bookings.total_price,
        bookings.status,
        users.full_name,
        cars.brand,
        cars.model
    FROM bookings
    INNER JOIN users ON bookings.user_id = users.id
    INNER JOIN cars ON bookings.car_id = cars.id
    ORDER BY bookings.id DESC
    LIMIT 5
");

$recent_bookings = $stmt->fetchAll();

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

            <?php if ($pending_bookings > 0): ?>
                <span style="
                    background: #e74c3c;
                    color: white;
                    padding: 3px 8px;
                    border-radius: 20px;
                    font-size: 12px;
                    margin-left: 8px;
                ">
                    <?php echo $pending_bookings; ?>
                </span>
            <?php endif; ?>
        </a>

        <a href="users.php" class="sidebar-link">
            Manage Users
        </a>

        <a href="reports.php" class="sidebar-link">
            Financial Reports
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


        <!-- WELCOME -->

        <section class="welcome-card">

            <div>
                <h2>Welcome back, <?php echo $full_name; ?>!</h2>
                <p>Manage your cars, users and bookings from here.</p>
            </div>

            <div class="welcome-icon">🚘</div>

        </section>


        <!-- STATISTICS -->

        <h2 class="section-heading">Overview</h2>

        <section class="stats-grid">

            <div class="stat-card">
                <div class="stat-icon">🚗</div>
                <p>Total Cars</p>
                <h2><?php echo $total_cars; ?></h2>
            </div>

            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <p>Available Cars</p>
                <h2><?php echo $available_cars; ?></h2>
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


        <!-- RECENT BOOKINGS -->

        <section class="admin-info">

            <h2>Recent Bookings</h2>

            <div style="overflow-x:auto;">

                <table style="width:100%; border-collapse:collapse; margin-top:20px;">

                    <thead>
                        <tr>
                            <th style="padding:12px; text-align:left;">ID</th>
                            <th style="padding:12px; text-align:left;">Customer</th>
                            <th style="padding:12px; text-align:left;">Car</th>
                            <th style="padding:12px; text-align:left;">Start Date</th>
                            <th style="padding:12px; text-align:left;">End Date</th>
                            <th style="padding:12px; text-align:left;">Price</th>
                            <th style="padding:12px; text-align:left;">Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (count($recent_bookings) > 0): ?>

                            <?php foreach ($recent_bookings as $booking): ?>

                                <tr>

                                    <td style="padding:12px;">
                                        #<?php echo (int)$booking["id"]; ?>
                                    </td>

                                    <td style="padding:12px;">
                                        <?php echo htmlspecialchars($booking["full_name"]); ?>
                                    </td>

                                    <td style="padding:12px;">
                                        <?php echo htmlspecialchars($booking["brand"] . " " . $booking["model"]); ?>
                                    </td>

                                    <td style="padding:12px;">
                                        <?php echo htmlspecialchars($booking["start_date"]); ?>
                                    </td>

                                    <td style="padding:12px;">
                                        <?php echo htmlspecialchars($booking["end_date"]); ?>
                                    </td>

                                    <td style="padding:12px;">
                                        €<?php echo number_format($booking["total_price"], 2); ?>
                                    </td>

                                    <td style="padding:12px;">
                                        <?php echo htmlspecialchars(ucfirst($booking["status"])); ?>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="7" style="padding:15px;">
                                    No bookings available yet.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

            <a href="bookings.php" class="btn" style="margin-top:20px; display:inline-block;">
                View All Bookings
            </a>

        </section>


        <!-- SYSTEM INFORMATION -->

        <section class="admin-info">

            <h2>System Information</h2>

            <p>
                Your CarRent administration panel is working successfully.
                Use the sidebar to manage your rental system.
            </p>

        </section>

    </main>

</div>

</body>
</html>
