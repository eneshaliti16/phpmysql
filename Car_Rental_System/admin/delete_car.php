
<?php
session_start();

require_once "../includes/config.php";

// Kontrollo nëse përdoruesi është Admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

// Kontrollo ID e veturës
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: cars.php");
    exit();
}

$id = (int) $_GET["id"];

try {

    $sql = "DELETE FROM cars WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    header("Location: cars.php");
    exit();

} catch (PDOException $e) {

    echo "<h2>Unable to delete this car!</h2>";
    echo "<p>This car may have existing bookings.</p>";
    echo '<a href="cars.php">Back to Manage Cars</a>';

}
?>
