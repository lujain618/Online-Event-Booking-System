<?php
// Start session to check if the admin is logged in
session_start();

// Check if the admin is logged in, if not, redirect to the login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: admin.php");
    exit();
}

// Dummy event data - Replace with real database queries
$events = [
    ['id' => 1, 'name' => 'Concert', 'date' => '2025-05-10', 'location' => 'Stadium', 'price' => '100'],
    ['id' => 2, 'name' => 'Conference', 'date' => '2025-06-15', 'location' => 'Conference Hall', 'price' => '50'],
    ['id' => 3, 'name' => 'Workshop', 'date' => '2025-07-20', 'location' => 'Community Center', 'price' => '30']
];

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events</title>
    
</head>
<body>
    <div class="admin-container">
        <!-- Side Menu -->
        <div class="side-menu">
            <h3>Admin Dashboard</h3>
            <ul>
                <li><a href="manageEvents.php">Manage Events</a></li>
                <li><a href="addEvent.php">Add Event</a></li>
                <li><a href="viewBookings.php">View Bookings</a></li>
                <li><a href="?logout=true">Logout</a></li>
            </ul>
        </div>

        <!-- Main Section -->
        <div class="main-section">
            <h2>Manage Events</h2>
            <table>
                <thead>
                    <tr>
                        <th>Event Name</th>
                        <th>Event Date</th>
                        <th>Location</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event) : ?>
                    <tr>
                        <td><?php echo $event['name']; ?></td>
                        <td><?php echo $event['date']; ?></td>
                        <td><?php echo $event['location']; ?></td>
                        <td>$<?php echo $event['price']; ?></td>
                        <td>
                            <a href="viewEvent.php?id=<?php echo $event['id']; ?>">View</a>
                            <a href="editEvent.php?id=<?php echo $event['id']; ?>">Edit</a>
                            <a href="deleteEvent.php?id=<?php echo $event['id']; ?>">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
