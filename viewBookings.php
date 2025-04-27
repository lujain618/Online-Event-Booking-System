<?php
include 'config.php';
checkAdminSession();

// Enable error display
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch all bookings with users and events
$stmt = $conn->prepare("
    SELECT 
        bookings.id,
        users.name AS customer_name,
        users.email AS customer_email,
        events.name AS event_name,
        events.date_time AS event_date,
        bookings.num_tickets,
        bookings.total_price,
        bookings.booking_date
    FROM bookings
    JOIN users ON bookings.user_id = users.id
    JOIN events ON bookings.event_id = events.id
    ORDER BY bookings.booking_date DESC
");
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Bookings - Admin Panel</title>
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

        /* Content */
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
        }

        h2 {
            text-align: center;
            color: #2E8659;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #3AA46F;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .no-bookings {
            text-align: center;
            color: #888;
            margin-top: 20px;
            font-size: 18px;
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
        <h2>View Bookings</h2>

        <?php if (count($bookings) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Customer Email</th>
                        <th>Booking Date</th>
                        <th>Event Name</th>
                        <th>Event Date</th>
                        <th>Number of Tickets Booked</th>
                        <th>Total Price (SAR)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?= htmlspecialchars($booking['customer_name']) ?></td>
                            <td><?= htmlspecialchars($booking['customer_email']) ?></td>
                            <td><?= date("F j, Y, g:i a", strtotime($booking['booking_date'])) ?></td>
                            <td><?= htmlspecialchars($booking['event_name']) ?></td>
                            <td><?= date("F j, Y, g:i a", strtotime($booking['event_date'])) ?></td>
                            <td><?= htmlspecialchars($booking['num_tickets']) ?></td>
                            <td><?= htmlspecialchars(number_format($booking['total_price'], 2)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-bookings">No bookings found.</div>
        <?php endif; ?>

    </div>

    <footer>
        &copy; <?= date("Y") ?> Event Booking System | Admin Panel
    </footer>

</div>

</body>
</html>
