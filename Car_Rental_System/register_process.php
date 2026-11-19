
<?php

require_once "includes/config.php";

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form information
    $full_name = trim($_POST["full_name"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    // Check empty fields
    if (
        empty($full_name) ||
        empty($username) ||
        empty($email) ||
        empty($password) ||
        empty($confirm_password)
    ) {
        die("Please fill in all fields.");
    }

    // Check email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Please enter a valid email address.");
    }

    // Check password length
    if (strlen($password) < 8) {
        die("Password must contain at least 8 characters.");
    }

    // Check password confirmation
    if ($password !== $confirm_password) {
        die("Passwords do not match.");
    }

    // Encrypt password
    $hashed_password = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    try {

        // Insert user into database
        $sql = "INSERT INTO users
                (full_name, username, email, password)
                VALUES
                (:full_name, :username, :email, :password)";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ":full_name" => $full_name,
            ":username" => $username,
            ":email" => $email,
            ":password" => $hashed_password
        ]);

        echo "Registration successful!";

        echo "<br><a href='login.php'>Login Now</a>";

    } catch (PDOException $e) {

        if ($e->getCode() == "23000") {

            die("Username or email already exists.");

        } else {

            die("Something went wrong. Please try again.");

        }

    }

} else {

    header("Location: register.php");
    exit();

}

?>
