
<?php
session_start();

require_once "../includes/config.php";

// Kontrolli i administratorit
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

// Vlera totale e rezervimeve te aprovuara
$total_revenue = $conn->query("
    SELECT COALESCE(SUM(total_price), 0)
    FROM bookings
    WHERE status = 'approved'
")->fetchColumn();

// Numri i rezervimeve te aprovuara
$approved_bookings = $conn->query("
    SELECT COUNT(*)
    FROM bookings
    WHERE status = 'approved'
")->fetchColumn();

// Numri i rezervimeve ne pritje
$pending_bookings = $conn->query("
    SELECT COUNT(*)
    FROM bookings
    WHERE status = 'pending'
")->fetchColumn();

// Raporti mujor per 6 muajt e fundit
$stmt = $conn->query("
    SELECT
        DATE_FORMAT(start_date, '%M %Y') AS month_name,
        COUNT(*) AS total_bookings,
        SUM(total_price) AS monthly_revenue
    FROM bookings
    WHERE status = 'approved'
    AND start_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY YEAR(start_date), MONTH(start_date)
    ORDER BY YEAR(start_date) DESC, MONTH(start_date) DESC
");

$monthly_reports = $stmt->fetchAll();

$full_name = htmlspecialchars($_SESSION["full_name"]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Reports - CarRent</title>

    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="admin-body">

<div class="admin-layout">

    <aside class="admin-sidebar">

        <h2 class="admin-logo">CAR<span>RENT</span></h2>

        <p class="sidebar-title">MAIN MENU</p>

        <a href="dashboard.php" class="sidebar-link">
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

        <a href="reports.php" class="sidebar-link active">
            Financial Reports
        </a>

        <div class="sidebar-bottom">
            <a href="../index.php" class="sidebar-link">
                Back to Website
            </a>
        </div>

    </aside>


    <main class="admin-main">

        <div class="admin-topbar">

            <div>
                <h1>Financial Reports</h1>
                <p>View your CarRent financial statistics.</p>
            </div>

            <div class="admin-profile">
                <div class="profile-circle">A</div>
                <span><?php echo $full_name; ?></span>
            </div>

        </div>


        <h2 class="section-heading">Financial Overview</h2>

        <section class="stats-grid">

            <div class="stat-card">
                <div class="stat-icon">💰</div>
                <p>Approved Booking Value</p>
                <h2>€<?php echo number_format($total_revenue, 2); ?></h2>
            </div>

            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <p>Approved Bookings</p>
                <h2><?php echo $approved_bookings; ?></h2>
            </div>

            <div class="stat-card">
                <div class="stat-icon">⏳</div>
                <p>Pending Bookings</p>
                <h2><?php echo $pending_bookings; ?></h2>
            </div>

        </section>


        <section class="admin-info">

            <h2>Monthly Revenue</h2>

            <p>
                Approved booking values grouped by pickup month
                for the last six months.
            </p>

            <div style="overflow-x:auto;">

                <table style="width:100%; border-collapse:collapse; margin-top:20px;">

                    <thead>
                        <tr>
                            <th style="padding:12px; text-align:left;">Month</th>
                            <th style="padding:12px; text-align:left;">Bookings</th>
                            <th style="padding:12px; text-align:left;">Revenue</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (count($monthly_reports) > 0): ?>

                            <?php foreach ($monthly_reports as $report): ?>

                                <tr>

                                    <td style="padding:12px;">
                                        <?php echo htmlspecialchars($report["month_name"]); ?>
                                    </td>

                                    <td style="padding:12px;">
                                        <?php echo (int)$report["total_bookings"]; ?>
                                    </td>

                                    <td style="padding:12px;">
                                        €<?php echo number_format($report["monthly_revenue"], 2); ?>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="3" style="padding:15px;">
                                    No approved bookings found in the last six months.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </section>

    </main>

</div>

</body>
</html>
