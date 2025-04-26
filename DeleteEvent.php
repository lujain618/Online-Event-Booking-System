<?php
session_start();
include('config.php');

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manageEvents.php");
    exit();
}

$event_id = (int)$_GET['id'];

$checkBookings = "SELECT COUNT(*) AS booking_count FROM bookings WHERE event_id = $event_id";
$result = mysqli_query($conn, $checkBookings);
$row = mysqli_fetch_assoc($result);

if ($row['booking_count'] > 0) {
    echo "<script>alert('Cannot delete event! Bookings exist for this event.'); window.location.href='manageEvents.php';</script>";
    exit();
}

$deleteEvent = "DELETE FROM events WHERE id = $event_id";
mysqli_query($conn, $deleteEvent);

header("Location: manageEvents.php");
exit();
?>