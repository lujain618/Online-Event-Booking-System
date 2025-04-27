<?php
include 'config.php';
checkAdminSession();

// Enable error display for debugging (optional)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $date_time = $_POST['date_time'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $max_tickets = $_POST['max_tickets'];

    // Handle image upload
    $imageName = $_FILES['image']['name'];
    $imageTmp = $_FILES['image']['tmp_name'];
    $uploadDir = 'uploads/';
    $uploadPath = $uploadDir . basename($imageName);

    if (move_uploaded_file($imageTmp, $uploadPath)) {
        $stmt = $conn->prepare("INSERT INTO events (name, date_time, location, price, image, max_tickets) 
                                VALUES (:name, :date_time, :location, :price, :image, :max_tickets)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':date_time', $date_time);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':image', $imageName);
        $stmt->bindParam(':max_tickets', $max_tickets);
        $stmt->execute();

        redirect('manageEvents.php');
    } else {
        echo "<script>alert('Failed to upload image. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Event - Admin Panel</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f7f3;
            display: flex;
        }

        /* Side Menu */
        .side-menu {
            width: 200px;
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
            list-style-type: disc; /* make it unordered list */
            padding-left: 20px;
        }

        .side-menu li {
            margin: 15px 0;
        }

        .side-menu a {
            color: white;
            text-decoration: underline; /* Underline links */
            font-weight: bold;
            font-size: 16px;
            transition: color 0.3s;
        }

        .side-menu a:hover {
            color: #d9f2e6; /* Light green on hover */
        }

        /* Page Content */
        .content {
            margin-left: 220px;
            padding: 30px;
            width: calc(100% - 220px);
        }

        .container {
            margin-top: 30px;
            background-color: white;
            padding: 30px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
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
        }

        input[type="submit"]:hover {
            background-color: #2E8659;
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
        <h2>Add New Event</h2>
        <form method="POST" enctype="multipart/form-data">
            <label>Event Name:</label>
            <input type="text" name="name" required>

            <label>Date & Time:</label>
            <input type="datetime-local" name="date_time" required>

            <label>Location:</label>
            <input type="text" name="location" required>

            <label>Ticket Price (SAR):</label>
            <input type="number" name="price" step="0.01" required>

            <label>Event Image:</label>
            <input type="file" name="image" accept="image/*" required>

            <label>Maximum Tickets:</label>
            <input type="number" name="max_tickets" required>

            <input type="submit" value="Add Event">
        </form>
    </div>

    <footer>
        &copy; <?= date("Y") ?> Event Booking System | Admin Panel
    </footer>
</div>

</body>
</html>

