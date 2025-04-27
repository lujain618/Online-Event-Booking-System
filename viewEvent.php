<?php
include 'config.php';
checkAdminSession();

// Enable error display
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch event details
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM events WHERE id = :id");
    $stmt->execute([':id' => $event_id]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Fetch total booked tickets for this event
    $bookedStmt = $conn->prepare("SELECT SUM(num_tickets) AS total_booked FROM bookings WHERE event_id = :id");
    $bookedStmt->execute([':id' => $event_id]);
    $bookedData = $bookedStmt->fetch(PDO::FETCH_ASSOC);

// Calculate available tickets
    $total_booked = $bookedData['total_booked'] ?? 0;
    $available_tickets = $event['max_tickets'] - $total_booked;
    if ($available_tickets < 0) {
        $available_tickets = 0; // Never show negative tickets
    }


    if (!$event) {
        echo "<script>alert('Event not found.');</script>";
        redirect('manageEvents.php');
    }
} else {
    echo "<script>alert('Invalid event ID.');</script>";
    redirect('manageEvents.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Event - Admin Panel</title>
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
            max-width: 700px;
            margin: 0 auto;
        }

        .event-title {
            font-size: 32px;
            color: #2E8659;
            margin-bottom: 30px;
            font-weight: bold;
            text-align: center;
        }

        .event-details {
            margin-bottom: 20px;
        }

        .event-detail {
            margin-bottom: 15px;
            font-size: 18px;
            color: #333;
        }

        .event-detail strong {
            width: 150px;
            display: inline-block;
            color: #2E8659;
			white-space: nowrap;
			margin-right: 10px;
        }

        .event-image {
            text-align: center;
            margin-top: 30px;
        }

        .event-image img {
            width: 100%;
            max-width: 500px;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .back-link {
            display: inline-block;
            margin-top: 30px;
            background-color: #3AA46F;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
            text-align: center;
        }

        .back-link:hover {
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
        <div class="event-title"><?= htmlspecialchars($event['name']) ?></div>

        <div class="event-details">
            <div class="event-detail">
                <strong>Date:</strong> <?= date("F j, Y", strtotime($event['date_time'])) ?>
            </div>

            <div class="event-detail">
                <strong>Time:</strong> <?= date("g:i a", strtotime($event['date_time'])) ?>
            </div>

            <div class="event-detail">
                <strong>Location:</strong> <?= htmlspecialchars($event['location']) ?>
            </div>

            <div class="event-detail">
                <strong>Ticket Price:</strong> <?= htmlspecialchars(number_format($event['price'], 2)) ?> SAR
            </div>

            <div class="event-detail">
                <strong>Available Tickets:</strong> <?= htmlspecialchars($available_tickets) ?>
            </div>
        </div>

        <div class="event-image">
            <img src="uploads/<?= htmlspecialchars($event['image']) ?>" alt="Event Image">
        </div>

        <div style="text-align: center;">
            <a href="manageEvents.php" class="back-link">Back to Events</a>
        </div>
    </div>

    <footer>
        &copy; <?= date("Y") ?> Event Booking System | Admin Panel
    </footer>
</div>

</body>
</html>

