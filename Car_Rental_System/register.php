
<?php
include "includes/header.php";
?>

<section class="auth-container">

    <div class="auth-box">

        <h2>Create Account</h2>

        <p class="auth-description">
            Register to start renting your favorite cars.
        </p>

        <form action="register_process.php" method="POST">

            <label>Full Name</label>
            <input type="text" name="full_name"
                   placeholder="Enter your full name" required>

            <label>Username</label>
            <input type="text" name="username"
                   placeholder="Choose a username" required>

            <label>Email</label>
            <input type="email" name="email"
                   placeholder="Enter your email" required>

            <label>Password</label>
            <input type="password" name="password"
                   placeholder="Create a password" required>

            <label>Confirm Password</label>
            <input type="password" name="confirm_password"
                   placeholder="Confirm your password" required>

            <button type="submit" class="auth-button">
                Register
            </button>

        </form>

        <p class="auth-link">
            Already have an account?
            <a href="login.php">Login here</a>
        </p>

    </div>

</section>

<?php
include "includes/footer.php";
?>
