
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Car Rental System</title>

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<header>

    <div class="logo">
        CAR<span>RENT</span>
    </div>

    
<nav>
    <a href="index.php">Home</a>
    <a href="#cars">Our Cars</a>
    <a href="#about">About Us</a>

    <?php if (isset($_SESSION["user_id"])): ?>

        <?php if ($_SESSION["role"] === "admin"): ?>
            <a href="admin/dashboard.php" class="btn">
                Admin Dashboard
            </a>
        <?php else: ?>
            <a href="my_bookings.php" class="btn">
                My Bookings
            </a>
        <?php endif; ?>

    <?php else: ?>
        <a href="login.php">Login</a>
        <a href="register.php" class="btn">Register</a>
    <?php endif; ?>

</nav>


</header>
</body>
</html>
