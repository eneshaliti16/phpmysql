
<?php
session_start();

require_once "includes/config.php";

// Kontrollojme nese perdoruesi eshte kycur
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error = "";

// Marrim ID e vetures
$car_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Marrim te dhenat e vetures
$stmt = $conn->prepare("
    SELECT * FROM cars
    WHERE id = ? AND status = 'available'
");

$stmt->execute([$car_id]);
$car = $stmt->fetch();

if (!$car) {
    die("Kjo veture nuk eshte e disponueshme.");
}

// Kur dergohet formulari
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    if (empty($start_date) || empty($end_date)) {

        $error = "Ju lutem plotesoni te dy datat.";

    } elseif ($start_date < date('Y-m-d')) {

        $error = "Data e marrjes nuk mund te jete ne te kaluaren.";

    } elseif ($end_date <= $start_date) {

        $error = "Data e kthimit duhet te jete pas dates se marrjes.";

    } else {

        $days = (strtotime($end_date) - strtotime($start_date)) / 86400;

        $total_price = $days * $car['price_per_day'];

        // Kontrollojme rezervimet ekzistuese
        $check = $conn->prepare("
            SELECT COUNT(*) FROM bookings
            WHERE car_id = ?
            AND status IN ('pending', 'approved')
            AND start_date < ?
            AND end_date > ?
        ");

        $check->execute([$car_id, $end_date, $start_date]);

        if ($check->fetchColumn() > 0) {

            $error = "Kjo veture eshte e rezervuar ne keto data.";

        } else {

            // Ruajme rezervimin
            $insert = $conn->prepare("
                INSERT INTO bookings
                (user_id, car_id, start_date, end_date, total_price, status)
                VALUES (?, ?, ?, ?, ?, 'pending')
            ");

            $insert->execute([
                $_SESSION['user_id'],
                $car_id,
                $start_date,
                $end_date,
                $total_price
            ]);

            header("Location: my_bookings.php");
            exit;
        }
    }
}

// Header perfshihet vetem nje here
include "includes/header.php";
?>

<div class="container">

    <h1>Rezervo veturen</h1>

    <h2>
        <?php echo htmlspecialchars($car['brand'] . " " . $car['model']); ?>
    </h2>

    <p>
        Çmimi për ditë:
        €<?php echo htmlspecialchars($car['price_per_day']); ?>
    </p>

    <?php if ($error != ""): ?>

        <p style="color:red;">
            <?php echo htmlspecialchars($error); ?>
        </p>

    <?php endif; ?>

    <form method="POST" class="car-form">

        <label>Data e marrjes:</label>

        <input
            type="date"
            name="start_date"
            min="<?php echo date('Y-m-d'); ?>"
            required
        >

        <label>Data e kthimit:</label>

        <input
            type="date"
            name="end_date"
            min="<?php echo date('Y-m-d'); ?>"
            required
        >

        <button type="submit" class="btn">
            Konfirmo rezervimin
        </button>

    </form>

</div>

<?php include "includes/footer.php"; ?>
