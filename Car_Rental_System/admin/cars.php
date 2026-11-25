
<?php
session_start();

require_once "../includes/config.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

// Numri i rezervimeve ne pritje
$pending_bookings = $conn->query("
    SELECT COUNT(*) FROM bookings
    WHERE status = 'pending'
")->fetchColumn();

// Marrja e veturave nga databaza
$sql = "SELECT * FROM cars ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$cars = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Cars - CarRent</title>

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

        <a href="cars.php" class="sidebar-link active">
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
                <h1>Manage Cars</h1>
                <p>View and manage all registered cars.</p>
            </div>

            <div class="admin-profile">
                <div class="profile-circle">A</div>
                <span>
                    <?php echo htmlspecialchars($_SESSION["full_name"]); ?>
                </span>
            </div>

        </div>

        <div class="admin-info">

            <div class="cars-heading">
                <div>
                    <h2>All Cars</h2>
                    <p>Total Cars: <?php echo count($cars); ?></p>
                </div>

                <a href="add_car.php" class="btn">
                    + Add New Car
                </a>
            </div>

            <div class="cars-table-container">

                <table class="cars-table">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>Year</th>
                            <th>Color</th>
                            <th>Price / Day</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (count($cars) > 0): ?>

                        <?php foreach ($cars as $car): ?>

                            <tr>
                                <td><?php echo $car["id"]; ?></td>

                                <td>
                                    <?php echo htmlspecialchars($car["brand"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($car["model"]); ?>
                                </td>

                                <td><?php echo $car["year"]; ?></td>

                                <td>
                                    <?php echo htmlspecialchars($car["color"] ?? "N/A"); ?>
                                </td>

                                <td>
                                    €<?php echo number_format($car["price_per_day"], 2); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($car["status"]); ?>
                                </td>
                                <td>
    <a href="edit_car.php?id=<?php echo $car['id']; ?>"
       class="edit-btn">
       Edit
    </a>

    <a href="delete_car.php?id=<?php echo $car['id']; ?>"
       class="delete-btn"
       onclick="return confirm('Are you sure you want to delete this car?')">
       Delete
    </a>
</td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="8">
                                No cars found. Add your first car!
                            </td>
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
