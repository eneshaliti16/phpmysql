
<?php
session_start();

require_once "../includes/config.php";

// Kontrolli i administratorit
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

// Numri i rezervimeve ne pritje
$pending_bookings = $conn->query("
    SELECT COUNT(*) FROM bookings
    WHERE status = 'pending'
")->fetchColumn();

// Marrja e perdoruesve nga databaza
$sql = "SELECT id, full_name, username, email, role, created_at
        FROM users
        ORDER BY id ASC";

$stmt = $conn->prepare($sql);
$stmt->execute();

$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Users - CarRent</title>

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

        <a href="users.php" class="sidebar-link active">
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
                <h1>Manage Users</h1>
                <p>View and manage registered users.</p>
            </div>

            <div class="admin-profile">
                <div class="profile-circle">A</div>

                <span>
                    <?php echo htmlspecialchars($_SESSION["full_name"]); ?>
                </span>
            </div>

        </div>

        <div class="admin-info">

            <h2>All Users</h2>

            <p>Total Users: <?php echo count($users); ?></p>

            <div class="cars-table-container">

                <table class="cars-table">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (count($users) > 0): ?>

                        <?php foreach ($users as $user): ?>

                            <tr>

                                <td>
                                    <?php echo $user["id"]; ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($user["full_name"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($user["username"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($user["email"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($user["role"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($user["created_at"]); ?>
                                </td>

                                <!-- ACTIONS -->
                                <td>
                                    <a href="edit_user.php?id=<?php echo $user['id']; ?>"
                                        class="edit-btn">
                                        Edit Role
                                            </a>

                                    <?php if ($user["id"] != $_SESSION["user_id"]): ?>

                                    <a href="delete_user.php?id=<?php echo $user['id']; ?>"
                                            class="delete-btn"
                                            onclick="return confirm('Are you sure you want to delete this user?')">
                                            Delete
                                    </a>

                                    <?php endif; ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="7">No users found.</td>
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
