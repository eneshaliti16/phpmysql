
<?php
session_start();

require_once "includes/config.php";

// Kontrollojme nese perdoruesi eshte kycur
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Marrim ID e rezervimit
if (isset($_GET['id'])) {

    $booking_id = (int) $_GET['id'];

    // Anulojme vetem rezervimin e ketij perdoruesi
    // dhe vetem nese statusi eshte pending

    $stmt = $conn->prepare("
        UPDATE bookings
        SET status = 'cancelled'
        WHERE id = ?
        AND user_id = ?
        AND status = 'pending'
    ");

    $stmt->execute([
        $booking_id,
        $_SESSION['user_id']
    ]);
}

// Kthehemi te My Bookings
header("Location: my_bookings.php");
exit;
?>
