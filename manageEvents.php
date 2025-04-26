<?php
session_start();
include('config.php');

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM events");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Manage Events</title>
</head>
<body>
<div>
    <h3>Admin Dashboard</h3>
    <ul>
        <li><a href="manageEvents.php">Manage Events</a></li>
        <li><a href="addEvent.php">Add Event</a></li>
        <li><a href="viewBookings.php">View Bookings</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<h2>Manage Events</h2>
<table border="1">
    <tr>
        <th>Event Name</th>
        <th>Event Date</th>
        <th>Location</th>
        <th>Price</th>
        <th>Actions</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td><?php echo htmlspecialchars($row['event_name']); ?></td>
        <td><?php echo htmlspecialchars($row['event_date']); ?></td>
        <td><?php echo htmlspecialchars($row['location']); ?></td>
        <td>$<?php echo htmlspecialchars($row['ticket_price']); ?></td>
        <td>
            <a href="viewEvent.php?id=<?php echo $row['id']; ?>">View</a>
            <a href="editEvent.php?id=<?php echo $row['id']; ?>">Edit</a>
            <a href="deleteEvent.php?id=<?php echo $row['id']; ?>">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
</body>
</html>