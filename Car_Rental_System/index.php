
<?php
require_once "includes/config.php";

// Marrja e vlerave nga formulari
$search = trim($_GET["search"] ?? "");
$max_price = $_GET["max_price"] ?? "";
$brand = $_GET["brand"] ?? "";
$sort = $_GET["sort"] ?? "newest";

// Marrja e markave nga databaza
$brand_stmt = $conn->query("
    SELECT DISTINCT brand
    FROM cars
    WHERE status = 'available'
    ORDER BY brand ASC
");

$brands = $brand_stmt->fetchAll(PDO::FETCH_COLUMN);

// Ndertimi i SQL
$sql = "SELECT * FROM cars WHERE status = 'available'";
$params = [];

// Kerkimi sipas markes ose modelit
if ($search != "") {
    $sql .= " AND (brand LIKE ? OR model LIKE ?)";
    $params[] = "%" . $search . "%";
    $params[] = "%" . $search . "%";
}

// Filtrimi sipas markes
if ($brand != "") {
    $sql .= " AND brand = ?";
    $params[] = $brand;
}

// Filtrimi sipas cmimit maksimal
if ($max_price != "" && is_numeric($max_price) && $max_price > 0) {
    $sql .= " AND price_per_day <= ?";
    $params[] = (float) $max_price;
}

// Renditja e veturave
switch ($sort) {

    case "price_low":
        $sql .= " ORDER BY price_per_day ASC";
        break;

    case "price_high":
        $sql .= " ORDER BY price_per_day DESC";
        break;

    case "year_new":
        $sql .= " ORDER BY year DESC";
        break;

    case "year_old":
        $sql .= " ORDER BY year ASC";
        break;

    default:
        $sql .= " ORDER BY id DESC";
        break;
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$cars = $stmt->fetchAll();

include "includes/header.php";
?>

<!-- HERO SECTION -->

<section class="hero">

    <div class="hero-content">

        <h1>
            Find Your Perfect
            <span>Car</span>
        </h1>

        <p>
            Discover the best cars for your journey.
            Rent your favorite car quickly, easily
            and safely with CarRent.
        </p>

        <a href="#cars" class="btn">
            Explore Cars
        </a>

    </div>

</section>


<!-- CARS SECTION -->

<section class="cars-section" id="cars">

    <h2 class="section-title">
        Our Available Cars
    </h2>

    <p class="section-description">
        Choose the perfect car for your next journey.
    </p>

    <!-- SEARCH AND FILTER FORM -->

    <form method="GET" action="index.php" class="car-form">

        <label>Search Brand or Model</label>

        <input
            type="text"
            name="search"
            placeholder="Example: Audi, BMW, Golf..."
            value="<?php echo htmlspecialchars($search); ?>"
        >

        <label>Choose Brand</label>

        <select name="brand">

            <option value="">All Brands</option>

            <?php foreach ($brands as $b): ?>

                <option
                    value="<?php echo htmlspecialchars($b); ?>"
                    <?php echo ($brand == $b) ? "selected" : ""; ?>
                >
                    <?php echo htmlspecialchars($b); ?>
                </option>

            <?php endforeach; ?>

        </select>

        <label>Maximum Price Per Day (€)</label>

        <input
            type="number"
            name="max_price"
            min="1"
            step="0.01"
            placeholder="Example: 50"
            value="<?php echo htmlspecialchars($max_price); ?>"
        >

        <label>Sort Cars By</label>

        <select name="sort">

            <option value="newest" <?php echo ($sort == "newest") ? "selected" : ""; ?>>
                Newest Added
            </option>

            <option value="price_low" <?php echo ($sort == "price_low") ? "selected" : ""; ?>>
                Price: Low to High
            </option>

            <option value="price_high" <?php echo ($sort == "price_high") ? "selected" : ""; ?>>
                Price: High to Low
            </option>

            <option value="year_new" <?php echo ($sort == "year_new") ? "selected" : ""; ?>>
                Year: Newest First
            </option>

            <option value="year_old" <?php echo ($sort == "year_old") ? "selected" : ""; ?>>
                Year: Oldest First
            </option>

        </select>

        <button type="submit" class="btn">
            Search Cars
        </button>

        <a href="index.php" class="cancel-link">
            Reset Filters
        </a>

    </form>


    <!-- CARS CONTAINER -->

    <div class="cars-container">

        <?php if (count($cars) > 0): ?>

            <?php foreach ($cars as $car): ?>

                <div class="car-card">

                    <?php if (!empty($car['image'])): ?>

                        <img
                            src="<?php echo htmlspecialchars($car['image']); ?>"
                            alt="<?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?>"
                        >

                    <?php else: ?>

                        <div class="car-image-placeholder">
                            No Image Available
                        </div>

                    <?php endif; ?>

                    <div class="car-info">

                        <h3>
                            <?php echo htmlspecialchars($car['brand'] . " " . $car['model']); ?>
                        </h3>

                        <p>
                            <?php echo htmlspecialchars($car['color'] ?? ""); ?>
                            |
                            <?php echo htmlspecialchars($car['year']); ?>
                        </p>

                        <div class="price">
                            €<?php echo number_format($car['price_per_day'], 2); ?> / Day
                        </div>

                        <a
                            href="rent.php?id=<?php echo (int)$car['id']; ?>"
                            class="btn"
                        >
                            Rent Now
                        </a>

                    </div>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <p>No cars found. Try different search filters.</p>

        <?php endif; ?>

    </div>

</section>


<!-- ABOUT SECTION -->

<section class="about" id="about">

    <h2 class="section-title">
        About CarRent
    </h2>

    <p>
        CarRent is a car rental platform that helps
        customers find and book their favorite cars.
        Our goal is to make car rental simple,
        comfortable and accessible for everyone.
    </p>

</section>


<?php include "includes/footer.php"; ?>
