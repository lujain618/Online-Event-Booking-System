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
    1 => ['id' => 1, 'name' => 'Concert', 'date' => '2025-05-10', 'location' => 'Stadium', 'price' => '100', 'image' => 'concert.jpg'],
    2 => ['id' => 2, 'name' => 'Conference', 'date' => '2025-06-15', 'location' => 'Conference Hall', 'price' => '50', 'image' => 'conference.jpg'],
    3 => ['id' => 3, 'name' => 'Workshop', 'date' => '2025-07-20', 'location' => 'Community Center', 'price' => '30', 'image' => 'workshop.jpg']
];

// Get the event ID from the URL
if (isset($_GET['id']) && isset($events[$_GET['id']])) {
    $event = $events[$_GET['id']];
} else {
    // If no event ID is found, redirect back to manage events page
    header("Location: manageEvents.php");
    exit();
}

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the updated event data from the form
    $name = $_POST['name'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    
    // Validate input fields (example: ensure no fields are empty)
    if (empty($name) || empty($date) || empty($location) || empty($price)) {
        $error_message = "All fields are required.";
    } else {
        // Process image upload (optional - ensure file handling and security)
        if ($image) {
            move_uploaded_file($_FILES['image']['tmp_name'], 'images/' . $image);
        } else {
            $image = $event['image']; // Keep existing image if no new one is uploaded
        }

        // Update event data (for now, this is dummy data; replace with a database update query)
        $events[$event['id']] = ['id' => $event['id'], 'name' => $name, 'date' => $date, 'location' => $location, 'price' => $price, 'image' => $image];
        
        // Redirect to manageEvents.php after update
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
    <title>Edit Event</title>
    
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
            <h2>Edit Event</h2>

            <?php if (isset($error_message)) : ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <form method="POST" action="editEvent.php?id=<?php echo $event['id']; ?>" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Event Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo $event['name']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="date">Event Date:</label>
                    <input type="date" id="date" name="date" value="<?php echo $event['date']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="location">Location:</label>
                    <input type="text" id="location" name="location" value="<?php echo $event['location']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="price">Ticket Price:</label>
                    <input type="number" id="price" name="price" value="<?php echo $event['price']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="image">Event Image:</label>
                    <input type="file" id="image" name="image">
                    <p>Current Image: <img src="images/<?php echo $event['image']; ?>" alt="Event Image" width="150"></p>
                </div>
                <button type="submit">Update Event</button>
            </form>

            <a href="manageEvents.php">Back to Manage Events</a>
        </div>
    </div>
</body>
</html>
