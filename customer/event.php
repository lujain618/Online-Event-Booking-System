<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/helpers.php'; 
checkCustomerSession();

// Check if event id is set
if (!isset($_GET['id'])) {
    redirect('home.php');
}

$event_id = (int) $_GET['id'];

// Fetch event details
$stmt = $conn->prepare("SELECT * FROM events WHERE id = :id");
$stmt->execute([':id' => $event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

// If event not found, redirect
if (!$event) {
    redirect('home.php');
}

// Calculate available tickets
$bookedStmt = $conn->prepare("SELECT SUM(num_tickets) AS total_booked FROM bookings WHERE event_id = :id");
$bookedStmt->execute([':id' => $event_id]);
$bookedData = $bookedStmt->fetch(PDO::FETCH_ASSOC);

$total_booked = $bookedData['total_booked'] ?? 0;
$available_tickets = $event['max_tickets'] - $total_booked;

// Handle form submission
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $quantity = (int) $_POST['quantity'];

    if ($quantity <= 0) {
        $error = "Please select at least 1 ticket.";
    } elseif ($quantity > $available_tickets) {
        $error = "Cannot book more than available tickets.";
    } else {
        // Your old code for adding to cart
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['event_id'] == $event['id']) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }
        unset($item);

        if (!$found) {
            $_SESSION['cart'][] = [
                'event_id' => $event['id'],
                'event_name' => $event['name'],
                'date_time' => $event['date_time'],
                'price' => $event['price'],
                'quantity' => $quantity
            ];
        }

        redirect('cart.php');
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Details - Event Booking System</title>
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        /* Your same style as before here */
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f7f3;
        }

        header, footer {
            background-color: #3AA46F;
            color: white;
            padding: 20px;
            text-align: center;
        }

        header h1 {
            margin: 0;
            font-size: 28px;
            padding: 10px;
            letter-spacing: 1px;
        }

        .top-links {
            margin-top: 10px;
            padding: 10px;
        }

        .top-links a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            background-color: #2E8659;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .top-links a:hover {
            background-color: #256b48;
        }

        main {
            padding: 40px;
            display: flex;
            justify-content: center;
        }

        .event-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
            max-width: 1000px;
            width: 100%;
            display: flex;
            gap: 30px;
        }

        .event-image {
            flex: 1;
        }

        .event-image img {
            width: 100%;
            height: 350px;
            object-fit: cover;
            border-radius: 10px;
        }

        .event-details {
            flex: 2;
        }

        .event-details h2 {
            color: #2E8659;
            margin-top: 0;
        }

        .event-details p {
            color: #666;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .book-tickets-box {
            background-color: #f9f9f9;
            margin-top: 25px;
            padding: 20px;
            border-radius: 10px;
        }

        .book-tickets-box h3 {
            color: #2E8659;
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            margin-top: 15px;
            width: 100%;
            padding: 12px;
            background-color: #3AA46F;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #2E8659;
        }

        .error {
            margin-top: 15px;
            color: red;
            font-weight: bold;
        }

        footer {
            margin-top: 40px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<header>
    <h1>Event Booking System</h1>
    <div class="top-links">
        <a href="home.php">Home</a>
        <a href="cart.php">Cart</a>
        <a href="logout.php">Logout</a>
    </div>
</header>

<main>

    <div class="event-container">
        <div class="event-image">
            <img src="uploads/<?= htmlspecialchars($event['image']) ?>" alt="Event Image">
        </div>

        <div class="event-details">
            <h2><?= htmlspecialchars($event['name']) ?></h2>
            <p><strong>Date:</strong> <?= date("F j, Y", strtotime($event['date_time'])) ?></p>
            <p><strong>Time:</strong> <?= date("g:i a", strtotime($event['date_time'])) ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($event['location']) ?></p>
            <p><strong>Price per ticket:</strong> SAR <?= htmlspecialchars(number_format($event['price'], 2)) ?></p>
            <p><strong>Available Tickets:</strong> <?= max($available_tickets, 0) ?></p>

            <div class="book-tickets-box">
                <h3>Book Tickets</h3>

                <?php if ($available_tickets > 0): ?>
                    <form method="POST">
                        <label for="quantity">Number of Tickets:</label>
                        <select name="quantity" id="quantity" required>
                            <option value="">Select</option>
                            <?php
                            for ($i = 1; $i <= $available_tickets; $i++) {
                                echo '<option value="' . $i . '">' . $i . '</option>';
                            }
                            ?>
                        </select>

                        <input type="submit" value="Add to Cart">
                    </form>
                <?php else: ?>
                    <p style="color:red; font-weight:bold;">Sold Out</p>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</main>

<footer>
    <p>&copy; <?= date("Y") ?> Event Booking System | All rights reserved.</p>
</footer>

</body>
</html>
