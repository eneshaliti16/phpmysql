
<?php
session_start();

require_once "../includes/config.php";

// Kontrolli i administratorit
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

// Ndryshimi i statusit te rezervimit

// Ndryshimi i statusit te rezervimit
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $booking_id = (int) $_POST["booking_id"];
    $status = $_POST["status"];

    if (in_array($status, ["approved", "rejected"])) {

        // Marrim rezervimin qe duam te ndryshojme
        $stmt = $conn->prepare("
            SELECT * FROM bookings
            WHERE id = ? AND status = 'pending'
        ");

        $stmt->execute([$booking_id]);
        $booking = $stmt->fetch();

        if ($booking) {

            // Kontrollojme nese vetura ka rezervim tjeter te aprovuar
            if ($status == "approved") {

                $check = $conn->prepare("
                    SELECT COUNT(*) FROM bookings
                    WHERE car_id = ?
                    AND id != ?
                    AND status = 'approved'
                    AND start_date <= ?
                    AND end_date >= ?
                ");

                $check->execute([
                    $booking["car_id"],
                    $booking_id,
                    $booking["end_date"],
                    $booking["start_date"]
                ]);

                $conflict = $check->fetchColumn();

                if ($conflict > 0) {

                    $_SESSION["booking_message"] =
                        "This car already has an approved booking for these dates!";

                } else {

                    $update = $conn->prepare("
                        UPDATE bookings
                        SET status = 'approved'
                        WHERE id = ?
                    ");

                    $update->execute([$booking_id]);

                    $_SESSION["booking_message"] =
                        "Booking approved successfully!";
                }

            } else {

                $update = $conn->prepare("
                    UPDATE bookings
                    SET status = 'rejected'
                    WHERE id = ?
                ");

                $update->execute([$booking_id]);

                $_SESSION["booking_message"] =
                    "Booking rejected successfully!";
            }
        }
    }

    header("Location: bookings.php");
    exit();
}

// Numri i rezervimeve ne pritje
$pending_bookings = $conn->query("
    SELECT COUNT(*) FROM bookings
    WHERE status = 'pending'
")->fetchColumn();

// Marrja e rezervimeve nga databaza
$sql = "SELECT
            bookings.*,
            users.full_name,
            cars.brand,
            cars.model
        FROM bookings
        INNER JOIN users ON bookings.user_id = users.id
        INNER JOIN cars ON bookings.car_id = cars.id
        ORDER BY bookings.id DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();

$bookings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Bookings - CarRent</title>

    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="admin-body">

<div class="admin-layout">

    <!-- SIDEBAR -->
    <aside class="admin-sidebar">

        <h2 class="admin-logo">CAR<span>RENT</span></h2>

        <p class="sidebar-title">MAIN MENU</p>

        <a href="dashboard.php" class="sidebar-link">
            Dashboard
        </a>

        <a href="cars.php" class="sidebar-link">
            Manage Cars
        </a>

        <a href="bookings.php" class="sidebar-link active">
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
                <h1>Manage Bookings</h1>
                <p>View and manage customer reservations.</p>
            </div>

            <div class="admin-profile">
                <div class="profile-circle">A</div>

                <span>
                    <?php echo htmlspecialchars($_SESSION["full_name"]); ?>
                </span>
            </div>

        </div>

        <div class="admin-info">

            <h2>All Bookings</h2>

                        <?php if (isset($_SESSION["booking_message"])): ?>
                        
                                <p class="booking-message">
                                    <?php
                                    echo htmlspecialchars($_SESSION["booking_message"]);
                                    unset($_SESSION["booking_message"]);
                                    ?>
                                </p>
                        
                        <?php endif; ?>

            <p>Total Bookings: <?php echo count($bookings); ?></p>

            <div class="cars-table-container">

                <table class="cars-table">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Car</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (count($bookings) > 0): ?>

                        <?php foreach ($bookings as $booking): ?>

                            <tr>

                                <td>
                                    <?php echo $booking["id"]; ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($booking["full_name"]); ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $booking["brand"] . " " . $booking["model"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($booking["start_date"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($booking["end_date"]); ?>
                                </td>

                                <td>
                                    €<?php echo number_format($booking["total_price"], 2); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($booking["status"]); ?>
                                </td>

                                <td>

                                    <?php if ($booking["status"] == "pending"): ?>

                                        <form method="POST" style="display:inline;">

                                            <input type="hidden"
                                                   name="booking_id"
                                                   value="<?php echo $booking['id']; ?>">

                                            <input type="hidden"
                                                   name="status"
                                                   value="approved">

                                            <button type="submit" class="edit-btn">
                                                Approve
                                            </button>

                                        </form>

                                        <form method="POST" style="display:inline;">

                                            <input type="hidden"
                                                   name="booking_id"
                                                   value="<?php echo $booking['id']; ?>">

                                            <input type="hidden"
                                                   name="status"
                                                   value="rejected">

                                            <button type="submit" class="delete-btn">
                                                Reject
                                            </button>

                                        </form>

                                    <?php else: ?>

                                        No actions

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="8">No bookings found.</td>
                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </main>

</div>

</body>
</html>
