<?php
// This is your database connection
$host = "localhost";
$dbname = "event_booking_db";
$username = "root";
$password = "root"; // for MAMP, password must be "root"

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Start session safely (only if not already started)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Helper functions
function redirect($url) {
    header("Location: " . $url);
    exit();
}

function checkAdminSession() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        redirect("admin.php");
    }
}

function checkCustomerSession() {
    if (!isset($_SESSION['user_id'])) {
        redirect("index.php");
    }
}
?>


