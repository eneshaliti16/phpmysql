
<?php
session_start();

require_once "../includes/config.php";

header("Content-Type: application/json");

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    http_response_code(403);
    echo json_encode(["count" => 0]);
    exit();
}

$pending_bookings = $conn->query("
    SELECT COUNT(*) FROM bookings
    WHERE status = 'pending'
")->fetchColumn();

echo json_encode([
    "count" => (int) $pending_bookings
]);
?>
