
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

    // Ruajme fotografine ekzistuese
    $image = $car["image"];

    if ($brand == "" || $model == "" || $year <= 0 || $price <= 0) {

        $message = "Please fill in all required fields correctly!";

    } else {

        // Kontrollojme nese eshte zgjedhur fotografi e re
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE) {

            if ($_FILES["image"]["error"] !== UPLOAD_ERR_OK) {

                $message = "Error uploading image!";

            } elseif ($_FILES["image"]["size"] > 5 * 1024 * 1024) {

                $message = "Image must be smaller than 5MB!";

            } else {

                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime = $finfo->file($_FILES["image"]["tmp_name"]);

                $allowed = [
                    "image/jpeg" => "jpg",
                    "image/png" => "png",
                    "image/webp" => "webp"
                ];

                if (!isset($allowed[$mime])) {

                    $message = "Only JPG, PNG and WEBP images are allowed!";

                } else {

                    $upload_dir = __DIR__ . "/../assets/images/";

                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0775, true);
                    }

                    if (!is_writable($upload_dir)) {

                        $message = "Images folder is not writable!";

                    } else {

                        $filename = bin2hex(random_bytes(16)) . "." . $allowed[$mime];

                        $upload_path = $upload_dir . $filename;

                        if (move_uploaded_file($_FILES["image"]["tmp_name"], $upload_path)) {

                            $image = "assets/images/" . $filename;

                        } else {

                            $message = "Could not upload the new image!";

                        }
                    }
                }
            }
        }

        // Ruajme ndryshimet ne databaze
        if ($message == "") {

            $sql = "UPDATE cars SET
                    brand = ?,
                    model = ?,
                    year = ?,
                    color = ?,
                    price_per_day = ?,
                    description = ?,
                    image = ?
                    WHERE id = ?";

            $stmt = $conn->prepare($sql);

            $stmt->execute([
                $brand,
                $model,
                $year,
                $color,
                $price,
                $description,
                $image,
                $id
            ]);

            header("Location: cars.php");
            exit();
        }
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
        <a href="users.php" class="sidebar-link">Manage Users</a>
        <a href="reports.php" class="sidebar-link">
            Financial Reports
        </a>

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

            <form method="POST" class="car-form" enctype="multipart/form-data">

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

                <label>Current Image</label>

                <?php if (!empty($car["image"])): ?>
                    <img src="../<?php echo htmlspecialchars($car["image"]); ?>"
                         alt="Car Image"
                         style="width:200px; height:130px; object-fit:cover; border-radius:10px; margin:10px 0;">
                <?php else: ?>
                    <p>No image available.</p>
                <?php endif; ?>

                <label>Choose New Image (optional)</label>
                <input type="file" name="image"
                    accept="image/jpeg,image/png,image/webp">

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
