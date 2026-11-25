
<?php
session_start();

require_once "includes/config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

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

<div class="container bookings-container">

    <h1 class="bookings-title">My Bookings</h1>
    <p class="bookings-subtitle">Manage and view your car reservations.</p>

    <?php if (count($bookings) > 0): ?>

        <div class="bookings-table-wrapper">

            <table class="bookings-table">

                <thead>
                    <tr>
                        <th>Car</th>
                        <th>Year</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($bookings as $booking): ?>

                        <tr>
                            <td class="car-name">
                                <?php echo htmlspecialchars($booking['brand'] . " " . $booking['model']); ?>
                            </td>

                            <td><?php echo htmlspecialchars($booking['year']); ?></td>

                            <td><?php echo htmlspecialchars($booking['start_date']); ?></td>

                            <td><?php echo htmlspecialchars($booking['end_date']); ?></td>

                            <td class="price">
                                €<?php echo htmlspecialchars($booking['total_price']); ?>
                            </td>

                            <td>
                                <?php
                                $status = $booking['status'];

                                if ($status == 'pending') {
                                    echo '<span class="status-pending">Pending</span>';
                                } elseif ($status == 'approved') {
                                    echo '<span class="status-approved">Approved</span>';
                                } elseif ($status == 'rejected') {
                                    echo '<span class="status-rejected">Rejected</span>';
                                } elseif ($status == 'cancelled') {
                                    echo '<span class="status-cancelled">Cancelled</span>';
                                }
                                ?>
                            </td>

                            <td>
                                <?php if ($booking['status'] == 'pending'): ?>
                                
                                    <a href="cancel_booking.php?id=<?php echo $booking['id']; ?>"
                                       class="cancel-btn"
                                       onclick="return confirm('Are you sure you want to cancel this booking?');">
                                        Cancel Booking
                                    </a>
                                
                                <?php else: ?>
                                
                                    <span class="no-action">No Action</span>
                                
                                <?php endif; ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php else: ?>

        <div class="no-bookings">
            <h3>No Bookings Yet</h3>
            <p>You have not made any reservations.</p>
        </div>

    <?php endif; ?>

</div>

<?php include "includes/footer.php"; ?>
