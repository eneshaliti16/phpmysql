
<?php include "includes/header.php"; ?>

<section class="auth-container">
    <h2>Login to Your Account</h2>

    <form action="login_process.php" method="POST">

        <input type="text" name="username"
               placeholder="Username" required>

        <input type="password" name="password"
               placeholder="Password" required>

        <button type="submit" class="btn">
            Login
        </button>

    </form>

    <p>Don't have an account?
        <a href="register.php">Register here</a>
    </p>
</section>

<?php include "includes/footer.php"; ?>
