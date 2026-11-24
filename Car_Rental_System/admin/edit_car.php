
<?php
session_start();

require_once "../includes/config.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: cars.php");
    exit();
}

$id = (int) $_GET["id"];

$stmt = $conn->prepare("SELECT * FROM cars WHERE id = ?");
$stmt->execute([$id]);
$car = $stmt->fetch();

if (!$car) {
    header("Location: cars.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $brand = trim($_POST["brand"]);
    $model = trim($_POST["model"]);
    $year = (int) $_POST["year"];
    $color = trim($_POST["color"]);
    $price = (float) $_POST["price"];
    $description = trim($_POST["description"]);

    if ($brand == "" || $model == "" || $year <= 0 || $price <= 0) {

        $message = "Please fill in all required fields correctly!";

    } else {

        $sql = "UPDATE cars SET
                brand = ?,
                model = ?,
                year = ?,
                color = ?,
                price_per_day = ?,
                description = ?
                WHERE id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            $brand,
            $model,
            $year,
            $color,
            $price,
            $description,
            $id
        ]);

        header("Location: cars.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Car - CarRent</title>

    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="admin-body">

<div class="admin-layout">

    <aside class="admin-sidebar">

        <h2 class="admin-logo">CAR<span>RENT</span></h2>

        <p class="sidebar-title">MAIN MENU</p>

        <a href="dashboard.php" class="sidebar-link">Dashboard</a>
        <a href="cars.php" class="sidebar-link active">Manage Cars</a>
        <a href="bookings.php" class="sidebar-link">Manage Bookings</a>
        <a href="users.php" class="sidebar-link">Manage Users</a>

        <div class="sidebar-bottom">
            <a href="../index.php" class="sidebar-link">Back to Website</a>
        </div>

    </aside>

    <main class="admin-main">

        <div class="admin-topbar">
            <div>
                <h1>Edit Car</h1>
                <p>Update vehicle information.</p>
            </div>
        </div>

        <div class="admin-info">

            <h2>Car Information</h2>

            <?php if ($message != ""): ?>
                <p class="form-error">
                    <?php echo htmlspecialchars($message); ?>
                </p>
            <?php endif; ?>

            <form method="POST" class="car-form">

                <label>Brand *</label>
                <input type="text" name="brand"
                    value="<?php echo htmlspecialchars($car["brand"]); ?>" required>

                <label>Model *</label>
                <input type="text" name="model"
                    value="<?php echo htmlspecialchars($car["model"]); ?>" required>

                <label>Year *</label>
                <input type="number" name="year"
                    value="<?php echo $car["year"]; ?>" min="1900" max="2100" required>

                <label>Color</label>
                <input type="text" name="color"
                    value="<?php echo htmlspecialchars($car["color"] ?? ""); ?>">

                <label>Price Per Day (€) *</label>
                <input type="number" name="price" step="0.01" min="0.01"
                    value="<?php echo $car["price_per_day"]; ?>" required>

                <label>Description</label>
                <textarea name="description" rows="5"><?php
                    echo htmlspecialchars($car["description"] ?? "");
                ?></textarea>

                <button type="submit" class="btn">
                    Update Car
                </button>

                <a href="cars.php" class="cancel-link">Cancel</a>

            </form>

        </div>

    </main>

</div>

</body>
</html>
