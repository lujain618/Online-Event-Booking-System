<?php
require_once '../includes/config.php';
require_once '../includes/helpers.php'; 
checkAdminSession();

// Enable error display (optional)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch all events
$stmt = $conn->query("SELECT * FROM events ORDER BY date_time ASC");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Events - Admin Panel</title>
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

        .action-buttons a {
            display: inline-block;
            margin: 0 5px;
            background-color: #3AA46F;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .action-buttons a:hover {
            background-color: #2E8659;
        }

        .no-events {
            text-align: center;
            color: #888;
            margin-top: 20px;
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
        <li><a href="../auth/logout.php">Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="content">

    <div class="container">
        <h2>Manage Events</h2>

        <?php if (count($events) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Event Name</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td><?= htmlspecialchars($event['name']) ?></td>
                            <td><?= date("F j, Y", strtotime($event['date_time'])) ?></td>
                            <td><?= htmlspecialchars($event['location']) ?></td>
                            <td class="action-buttons">
                                <a href="viewEvent.php?id=<?= $event['id'] ?>">View</a>
                                <a href="editEvent.php?id=<?= $event['id'] ?>">Edit</a>
                                <a href="deleteEvent.php?id=<?= $event['id'] ?>">Delete</a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-events">No events found.</div>
        <?php endif; ?>

    </div>

    <footer>
        &copy; <?= date("Y") ?> Event Booking System | Admin Panel
    </footer>
</div>

</body>
</html>
