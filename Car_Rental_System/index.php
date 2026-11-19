
<?php
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
        Our Popular Cars
    </h2>

    <p class="section-description">
        Choose the perfect car for your next journey.
    </p>

    <div class="cars-container">

        <!-- CAR 1 -->

        <div class="car-card">

            <img
                src="https://images.unsplash.com/photo-1492144534655-ae79c964c9d7"
                alt="BMW Car"
            >

            <div class="car-info">

                <h3>BMW Series 5</h3>

                <p>Automatic | Diesel | 2022</p>

                <div class="price">
                    €65 / Day
                </div>

                <a href="login.php" class="btn">
                    Rent Now
                </a>

            </div>

        </div>


        <!-- CAR 2 -->

        <div class="car-card">

            <img
                src="https://images.unsplash.com/photo-1503376780353-7e6692767b70"
                alt="Porsche Car"
            >

            <div class="car-info">

                <h3>Porsche 911</h3>

                <p>Automatic | Petrol | 2023</p>

                <div class="price">
                    €120 / Day
                </div>

                <a href="login.php" class="btn">
                    Rent Now
                </a>

            </div>

        </div>


        <!-- CAR 3 -->

        <div class="car-card">

            <img
                src="https://images.unsplash.com/photo-1552519507-da3b142c6e3d"
                alt="Chevrolet Car"
            >

            <div class="car-info">

                <h3>Chevrolet Camaro</h3>

                <p>Automatic | Petrol | 2022</p>

                <div class="price">
                    €90 / Day
                </div>

                <a href="login.php" class="btn">
                    Rent Now
                </a>

            </div>

        </div>

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


<?php
include "includes/footer.php";
?>
