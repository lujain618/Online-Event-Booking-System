<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/helpers.php'; 
checkCustomerSession();

// Handle Reserve Tickets
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $stmt = $conn->prepare("INSERT INTO bookings (user_id, event_id, num_tickets, total_price) VALUES (:user_id, :event_id, :num_tickets, :total_price)");
        $stmt->execute([
            ':user_id' => $_SESSION['user_id'],
            ':event_id' => $item['event_id'],
            ':num_tickets' => $item['quantity'],
            ':total_price' => $item['price'] * $item['quantity']
        ]);
    }

    unset($_SESSION['cart']);
    redirect('home.php');
}

$cart = $_SESSION['cart'] ?? [];
$cartCount = 0;
foreach ($cart as $item) {
    $cartCount += $item['quantity'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cart - Event Booking System</title>
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f7f3;
        }

        header {
            background-color: #3AA46F;
            color: white;
            padding: 15px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-right span {
            font-size: 16px;
            margin-right: 10px;
        }

        .cart-btn {
            background-color: #2E8659;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none;
        }

        .logout-btn {
            background-color: #D9534F;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none;
        }

        main {
            padding: 40px;
            display: flex;
            justify-content: center;
        }

        .cart-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
            max-width: 800px;
            width: 100%;
        }

        .datetime {
            text-align: center;
            margin-top: 5px;
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #f0f0f0; /* Light gray */
            color: #333;
            font-weight: bold;
        }

        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            color: #2E8659;
            margin-top: 10px;
        }

        .reserve-btn {
            width: 100%;
            margin-top: 20px;
            padding: 15px;
            background-color: #3AA46F;
            color: white;
            font-size: 18px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .reserve-btn:hover {
            background-color: #2E8659;
        }

        .empty-cart {
            text-align: center;
            font-size: 18px;
            color: #666;
        }

        footer {
            margin-top: 40px;
            font-size: 14px;
            text-align: center;
            background-color: #3AA46F;
            color: white;
            padding: 15px;
        }
    </style>
</head>
<body>

<header>
    <h1>Event Booking System</h1>

    <div class="header-right">
        <span>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
        <a href="cart.php" class="cart-btn">Cart (<?= $cartCount ?>)</a>
        <a href="../auth/logout.php" class="logout-btn">Logout</a>
    </div>
</header>

<main>

    <div class="cart-container">

        <h2 style="text-align:center; color:#2E8659;">Your Cart</h2>
        <div class="datetime">Current Date and Time: <?= date("F j, Y, g:i a") ?></div>

        <?php if (!empty($cart)): ?>
            <form method="POST">
                <table>
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Date</th>
                            <th>Quantity</th>
                            <th>Price per Ticket (SAR)</th>
                            <th>Total (SAR)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $grandTotal = 0;
                        foreach ($cart as $item):
                            $eventDate = date("F j, Y", strtotime($item['date_time']));
                            $itemTotal = $item['price'] * $item['quantity'];
                            $grandTotal += $itemTotal;
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($item['event_name']) ?></td>
                            <td><?= $eventDate ?></td>
                            <td><?= htmlspecialchars($item['quantity']) ?></td>
                            <td><?= number_format($item['price'], 2) ?></td>
                            <td><?= number_format($itemTotal, 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="total">Total Price: SAR <?= number_format($grandTotal, 2) ?></div>

                <button type="submit" class="reserve-btn">Reserve Tickets</button>
            </form>
        <?php else: ?>
            <div class="empty-cart">Your cart is empty.</div>
        <?php endif; ?>

    </div>

</main>

<footer>
    <p>&copy; <?= date("Y") ?> Event Booking System | All rights reserved.</p>
</footer>

</body>
</html>


