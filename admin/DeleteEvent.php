<?php
// Start session to check if the admin is logged in
session_start();

// Check if the admin is logged in, if not, redirect to the login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: admin.php");
    exit();
}

// Dummy event data - Replace with a real database query based on the event ID
$events = [
    1 => ['id' => 1, 'name' => 'Concert', 'date' => '2025-05-10', 'location' => 'Stadium', 'price' => '100', 'image' => 'concert.jpg', 'bookings' => 0],
    2 => ['id' => 2, 'name' => 'Conference', 'date' => '2025-06-15', 'location' => 'Conference Hall', 'price' => '50', 'image' => 'conference.jpg', 'bookings' => 5],
    3 => ['id' => 3, 'name' => 'Workshop', 'date' => '2025-07-20', 'location' => 'Community Center', 'price' => '30', 'image' => 'workshop.jpg', 'bookings' => 0]
];

// Get the event ID from the URL
if (isset($_GET['id']) && isset($events[$_GET['id']])) {
    $event = $events[$_GET['id']];
} else {
    // If no event ID is found, redirect back to manage events page
    header("Location: manageEvents.php");
    exit();
}

// Check if the event has bookings linked to it
if ($event['bookings'] > 0) {
    $error_message = "You cannot delete an event that has bookings linked to it.";
} else {
    // Handle the delete request if confirmed
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Remove the event from the events array (replace with database delete query)
        unset($events[$event['id']]);
        
        // Redirect to manage events page after deletion
        header("Location: manageEvents.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">
    <title>Delete Event</title>
    
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
            <h2>Delete Event</h2>

            <?php if (isset($error_message)) : ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php else : ?>
                <div class="event-details">
                    <p><strong>Event Name:</strong> <?php echo $event['name']; ?></p>
                    <p><strong>Event Date:</strong> <?php echo $event['date']; ?></p>
                    <p><strong>Location:</strong> <?php echo $event['location']; ?></p>
                    <p><strong>Ticket Price:</strong> $<?php echo $event['price']; ?></p>
                    <p><strong>Event Image:</strong> <img src="images/<?php echo $event['image']; ?>" alt="Event Image" width="150"></p>
                </div>

                <!-- Confirmation Form -->
                <form method="POST" action="">
                    <p>Are you sure you want to delete this event?</p>
                    <button type="submit" class="delete-btn">Yes, Delete this Event</button>
                    <a href="manageEvents.php" class="cancel-btn">Cancel</a>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
