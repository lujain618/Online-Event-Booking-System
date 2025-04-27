<?php
include 'config.php';
checkAdminSession();

// Enable error display (optional)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch existing event
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM events WHERE id = :id");
    $stmt->execute([':id' => $event_id]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        echo "<script>alert('Event not found.');</script>";
        redirect('manageEvents.php');
    }
} else {
    echo "<script>alert('Invalid event ID.');</script>";
    redirect('manageEvents.php');
}

// Handle update form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $date_time = $_POST['date_time'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $max_tickets = $_POST['max_tickets'];

    // Handle image upload if a new image was uploaded
    if (!empty($_FILES['image']['name'])) {
        $imageName = $_FILES['image']['name'];
        $imageTmp = $_FILES['image']['tmp_name'];
        $uploadDir = 'uploads/';
        $uploadPath = $uploadDir . basename($imageName);

        move_uploaded_file($imageTmp, $uploadPath);
    } else {
        // No new image uploaded, keep old image
        $imageName = $event['image'];
    }

    // Update event in database
    $stmt = $conn->prepare("UPDATE events SET name = :name, date_time = :date_time, location = :location, price = :price, image = :image, max_tickets = :max_tickets WHERE id = :id");
    $stmt->execute([
        ':name' => $name,
        ':date_time' => $date_time,
        ':location' => $location,
        ':price' => $price,
        ':image' => $imageName,
        ':max_tickets' => $max_tickets,
        ':id' => $event_id
    ]);

    echo "<script>alert('Event updated successfully.');</script>";
    redirect('manageEvents.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Event - Admin Panel</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f7f3;
            display: flex;
        }

        /* Side Menu */
        .side-menu {
            width: 220px;
            background-color: #3AA46F;
            min-height: 100vh;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            position: fixed;
            color: white;
        }

        .side-menu h1 {
            font-size: 22px;
            margin-bottom: 30px;
            text-align: center;
            font-weight: bold;
        }

        .side-menu ul {
            list-style-type: disc;
            padding-left: 20px;
        }

        .side-menu li {
            margin: 15px 0;
        }

        .side-menu a {
            color: white;
            text-decoration: underline;
            font-weight: bold;
            font-size: 16px;
            transition: color 0.3s;
        }

        .side-menu a:hover {
            color: #d9f2e6;
        }

        /* Page Content */
        .content {
            margin-left: 220px;
            padding: 30px;
            width: calc(100% - 220px);
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        h2 {
            text-align: center;
            color: #2E8659;
            margin-bottom: 20px;
        }

        label {
            margin-top: 10px;
            display: block;
            color: #333;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        input[type="datetime-local"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            background-color: #3AA46F;
            color: white;
            border: none;
            padding: 12px 20px;
            width: 100%;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
			margin: 5px;
        }

        input[type="submit"]:hover {
            background-color: #2E8659;
        }

        .current-image {
            margin-top: 10px;
            text-align: center;
        }

        .current-image img {
            max-width: 300px;
            margin-top: 10px;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }

        footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #888;
        }
    </style>
</head>
<body>

<!-- Side Menu -->
<div class="side-menu">
    <h1>Admin Panel</h1>
    <ul>
        <li><a href="manageEvents.php">Manage Events</a></li>
        <li><a href="addEvent.php">Add Event</a></li>
        <li><a href="viewBookings.php">View Bookings</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="content">

    <div class="container">
        <h2>Edit Event</h2>

        <form method="POST" enctype="multipart/form-data">
            <label>Event Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($event['name']) ?>" required>

            <label>Date & Time:</label>
            <input type="datetime-local" name="date_time" value="<?= date('Y-m-d\TH:i', strtotime($event['date_time'])) ?>" required>

            <label>Location:</label>
            <input type="text" name="location" value="<?= htmlspecialchars($event['location']) ?>" required>

            <label>Ticket Price (SAR):</label>
            <input type="number" name="price" step="0.01" value="<?= htmlspecialchars($event['price']) ?>" required>

            <label>Maximum Tickets:</label>
            <input type="number" name="max_tickets" value="<?= htmlspecialchars($event['max_tickets']) ?>" required>

            <label>Event Image:</label>
            <input type="file" name="image" accept="image/*">

            <div class="current-image">
                <p>Current Image:</p>
                <img src="uploads/<?= htmlspecialchars($event['image']) ?>" alt="Current Event Image">
            </div>

            <input type="submit" value="Update Event">
        </form>
    </div>

    <footer>
        &copy; <?= date("Y") ?> Event Booking System | Admin Panel
    </footer>
</div>

</body>
</html>
