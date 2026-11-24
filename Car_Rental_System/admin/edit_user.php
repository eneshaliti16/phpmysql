
<?php
session_start();

require_once "../includes/config.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: users.php");
    exit();
}

$id = (int) $_GET["id"];

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: users.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $role = $_POST["role"];

    if ($role != "admin" && $role != "user") {
        $message = "Invalid role!";
    } else {

        $stmt = $conn->prepare(
            "UPDATE users SET role = ? WHERE id = ?"
        );

        $stmt->execute([$role, $id]);

        // Përditëso rolin në sesion nëse është llogaria jote
        if ($id == $_SESSION["user_id"]) {
            $_SESSION["role"] = $role;
        }

        header("Location: users.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - CarRent</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="admin-body">

<div class="admin-layout">

    <aside class="admin-sidebar">
        <h2 class="admin-logo">CAR<span>RENT</span></h2>

        <p class="sidebar-title">MAIN MENU</p>

        <a href="dashboard.php" class="sidebar-link">Dashboard</a>
        <a href="cars.php" class="sidebar-link">Manage Cars</a>
        <a href="bookings.php" class="sidebar-link">Manage Bookings</a>
        <a href="users.php" class="sidebar-link active">Manage Users</a>

        <div class="sidebar-bottom">
            <a href="../index.php" class="sidebar-link">Back to Website</a>
        </div>
    </aside>

    <main class="admin-main">

        <div class="admin-topbar">
            <div>
                <h1>Edit User</h1>
                <p>Change user account role.</p>
            </div>
        </div>

        <div class="admin-info">

            <h2>User Information</h2>

            <p>
                Username:
                <strong><?php echo htmlspecialchars($user["username"]); ?></strong>
            </p>

            <?php if ($message != ""): ?>
                <p class="form-error">
                    <?php echo htmlspecialchars($message); ?>
                </p>
            <?php endif; ?>

            <form method="POST" class="car-form">

                <label>Select Role</label>

                <select name="role" required>
                    <option value="user"
                        <?php if ($user["role"] == "user") echo "selected"; ?>>
                        User
                    </option>

                    <option value="admin"
                        <?php if ($user["role"] == "admin") echo "selected"; ?>>
                        Admin
                    </option>
                </select>

                <button type="submit" class="btn">
                    Update Role
                </button>

                <a href="users.php" class="cancel-link">Cancel</a>

            </form>

        </div>

    </main>

</div>

</body>
</html>
