<?php
include 'config.php';
checkAdminSession();

// Enable error display
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch event
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM events WHERE id = :id");
    $stmt->execute([':id' => $event_id]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        echo "<script>alert('Event not found.');</script>";
        redirect('manageEvents.php');
    }

    // Fetch bookings
    $stmtBooking = $conn->prepare("SELECT COUNT(*) FROM bookings WHERE event_id = :event_id");
    $stmtBooking->execute([':event_id' => $event_id]);
    $bookingsCount = $stmtBooking->fetchColumn();
} else {
    echo "<script>alert('Invalid event ID.');</script>";
    redirect('manageEvents.php');
}

// Handle deletion confirmation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($bookingsCount > 0) {
        echo "<script>alert('Cannot delete. This event has bookings.');</script>";
        redirect('manageEvents.php');
    } else {
        $deleteStmt = $conn->prepare("DELETE FROM events WHERE id = :id");
        $deleteStmt->execute([':id' => $event_id]);

        echo "<script>alert('Event deleted successfully.');</script>";
        redirect('manageEvents.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Event - Admin Panel</title>
    <style>
        body {
           margin: 0;
           font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
           background-color: #f9f7f3; /* Normal background */
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
            background-color: #ffe5e5; /* Light red only for the container */
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 700px;
            margin: 0 auto;
        }


        h2 {
            text-align: center;
            color: #d9534f;
            margin-bottom: 20px;
        }

        .event-detail {
            margin-bottom: 15px;
            font-size: 18px;
            color: #333;
        }

        .event-detail strong {
            width: 170px;
            display: inline-block;
            color: #2E8659;
        }

        .event-image {
            text-align: center;
            margin-top: 20px;
        }

        .event-image img {
            width: 100%;
            max-width: 400px;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .confirm-text {
            text-align: center;
            font-size: 20px;
            color: #d9534f;
            margin-top: 30px;
            margin-bottom: 20px;
        }

        .confirm-buttons {
            text-align: center;
        }

        .confirm-buttons button {
            background-color: #d9534f;
            color: white;
            border: none;
            padding: 12px 20px;
            margin: 10px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .confirm-buttons button:hover {
            background-color: #c9302c;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
            color: #3AA46F;
            text-decoration: underline;
            font-size: 16px;
        }

        footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #888;
        }

        .warning-text {
            color: #d9534f;
            font-weight: bold;
            margin-top: 5px;
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
        <h2>Delete Event</h2>

        <div class="event-detail">
            <strong>Event Name:</strong> <?= htmlspecialchars($event['name']) ?>
        </div>

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
            <strong>Available Tickets:</strong> <?= htmlspecialchars($event['max_tickets']) ?>
        </div>

        <div class="event-detail">
            <strong>Bookings:</strong> 
            <?= htmlspecialchars($bookingsCount) ?>
            <?php if ($bookingsCount > 0): ?>
                <span class="warning-text">(This event has bookings and cannot be deleted)</span>
            <?php endif; ?>
        </div>

        <div class="event-image">
            <img src="uploads/<?= htmlspecialchars($event['image']) ?>" alt="Event Image">
        </div>

        <?php if ($bookingsCount == 0): ?>
            <div class="confirm-text">
                Are you sure you want to delete this event?
            </div>

            <form method="POST" class="confirm-buttons">
                <button type="submit">Yes, Delete this Event</button>
            </form>
        <?php endif; ?>

        <div style="text-align: center;">
            <a href="manageEvents.php" class="back-link">Cancel and Go Back</a>
        </div>

    </div>

    <footer>
        &copy; <?= date("Y") ?> Event Booking System | Admin Panel
    </footer>
</div>

</body>
</html>

