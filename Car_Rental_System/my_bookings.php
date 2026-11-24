
<?php
session_start();

require_once "includes/config.php";

// Kontrollojme nese perdoruesi eshte kycur
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Marrim rezervimet e perdoruesit
$stmt = $conn->prepare("
    SELECT
        bookings.*,
        cars.brand,
        cars.model,
        cars.year,
        cars.image
    FROM bookings
    INNER JOIN cars ON bookings.car_id = cars.id
    WHERE bookings.user_id = ?
    ORDER BY bookings.id DESC
");

$stmt->execute([$_SESSION['user_id']]);

$bookings = $stmt->fetchAll();

include "includes/header.php";
?>

<div class="container">

    <h1>My Bookings</h1>

    <?php if (count($bookings) > 0): ?>

        <table>

            <tr>
                <th>Car</th>
                <th>Year</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Total Price</th>
                <th>Status</th>
            </tr>

            <?php foreach ($bookings as $booking): ?>

                <tr>

                    <td>
                        <?php
                        echo htmlspecialchars(
                            $booking['brand'] . " " . $booking['model']
                        );
                        ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($booking['year']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($booking['start_date']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($booking['end_date']); ?>
                    </td>

                    <td>
                        €<?php echo htmlspecialchars($booking['total_price']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($booking['status']); ?>
                    </td>

                </tr>

            <?php endforeach; ?>

        </table>

    <?php else: ?>

        <p>You have no bookings yet.</p>

    <?php endif; ?>

</div>

<?php include "includes/footer.php"; ?>
