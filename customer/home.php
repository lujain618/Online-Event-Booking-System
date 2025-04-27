<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/helpers.php';
checkCustomerSession();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home - Event Booking System</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f7f3;
        }

        header, footer {
            background-color: #3AA46F; /* Green */
            color: white;
            padding: 20px;
            text-align: center;
        }

        .header-logo {
            width: 250px;
            height: auto;
        }

        header h1 {
            margin: 0;
            font-size: 28px;
            letter-spacing: 1px;
            padding: 10px;
        }

        .top-links {
            margin-top: 10px;
            padding: 10px;
        }

        .top-links a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            background-color: #2E8659; /* Darker Green Button */
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .top-links a:hover {
            background-color: #256b48; /* Even Darker Green on Hover */
        }

        main {
            padding: 40px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 25px;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            width: 30%;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
            position: relative;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 6px 15px rgba(0,0,0,0.2);
        }

        .card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        .card-body {
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .card-body h3 {
            margin: 10px 0;
            font-size: 22px;
            color: #2E8659;
        }

        .card-body p {
            color: #666;
            margin-bottom: 15px;
        }

        .book-btn {
            background-color: #3AA46F; /* Green Button */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0; /* Hidden by default */
            transition: opacity 0.3s ease;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .card:hover .book-btn {
            opacity: 1; /* Visible when hovered */
        }

        .book-btn:hover {
            background-color: #2E8659; /* Darker Green on hover */
        }

        footer {
            margin-top: 40px;
            font-size: 14px;
        }

        @media (max-width: 992px) {
            .card {
                width: 45%;
            }
        }

        @media (max-width: 600px) {
            .card {
                width: 90%;
            }
        }
    </style>
</head>
<body>

<header>
    <img src="../styles/EventLogo.png" alt="Event Booking Logo" class="header-logo">
    <h1>Event Booking System</h1>
    <div>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>!</div>
    <div class="top-links">
        <a href="cart.php">Cart</a>
        <a href="../auth/logout.php">Logout</a>
    </div>
</header>

<main>
    <?php
    $stmt = $conn->query("SELECT * FROM events ORDER BY date_time ASC");
    while ($event = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<div class="card">';
        echo '<img src="../admin/uploads/' . htmlspecialchars($event['image']) . '" alt="Event">';
        echo '<div class="card-body">';
        echo '<h3>' . htmlspecialchars($event['name']) . '</h3>';
        echo '<p>' . date("F j, Y, g:i a", strtotime($event['date_time'])) . '</p>';
        echo '<form method="GET" action="event.php">';
        echo '<input type="hidden" name="id" value="' . htmlspecialchars($event['id']) . '">';
        echo '<button type="submit" class="book-btn">Book Now</button>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
    }
    ?>
</main>

<footer>
    <p>&copy; <?= date("Y") ?> Event Booking System | All rights reserved.</p>
</footer>

</body>
</html>

