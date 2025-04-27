<?php
session_start();
include('config.php');

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin.php");
    exit();
}

$query = "SELECT bookings.id, users.name AS customer_name, users.email AS customer_email, 
          bookings.booking_date, events.event_name, events.event_date, 
          bookings.num_tickets, bookings.total_price
          FROM bookings 
          INNER JOIN users ON bookings.user_id = users.id 
          INNER JOIN events ON bookings.event_id = events.id";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>View Bookings</title>
</head>
<body>
<h2>All Bookings</h2>
<table border="1">
    <tr>
        <th>Customer Name</th>
        <th>Customer Email</th>
        <th>Booking Date</th>
        <th>Event Name</th>
        <th>Event Date</th>
        <th>Number of Tickets</th>
        <th>Total Price</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
        <td><?php echo htmlspecialchars($row['customer_email']); ?></td>
        <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
        <td><?php echo htmlspecialchars($row['event_name']); ?></td>
        <td><?php echo htmlspecialchars($row['event_date']); ?></td>
        <td><?php echo (int)$row['num_tickets']; ?></td>
        <td>$<?php echo number_format($row['total_price'], 2); ?></td>
    </tr>
    <?php endwhile; ?>
</table>
</body>
</html>
