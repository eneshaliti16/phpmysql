
<?php
session_start();

require_once "../includes/config.php";

// Kontrollo nese eshte Admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

// Kontrollo ID
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: users.php");
    exit();
}

$id = (int) $_GET["id"];

// Mos lejo fshirjen e llogarise tende
if ($id == $_SESSION["user_id"]) {
    header("Location: users.php");
    exit();
}

// Fshij perdoruesin
try {

    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    header("Location: users.php");
    exit();

} catch (PDOException $e) {

    echo "Unable to delete this user.";
    echo '<br><a href="users.php">Back to Users</a>';

}
?>
