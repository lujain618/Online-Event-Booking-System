<?php
session_start();
include 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cart</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        p {
            font-size: 18px;
            margin: 10px 0;
            color: #555;
        }
        strong {
            color: #000;
        }
        button {
            background-color: #4CAF50; /* أخضر */
            color: white;
            padding: 12px 25px;
            margin-top: 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            width: 100%;
            font-size: 18px;
        }
        button:hover {
            background-color: #45a049;
        }
        form {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
<?php
// التحقق من طريقة الطلب ومن صحة البيانات
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id']) && isset($_POST['num_tickets'])) {
    $event_id = intval($_POST['event_id']);
    $num_tickets = intval($_POST['num_tickets']);

    $query = "SELECT * FROM events WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $event_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $price_per_ticket = floatval($row['price']);
        $total_price = $price_per_ticket * $num_tickets;

        echo "<h1>Booking Summary</h1>";
        echo "<p><strong>Event:</strong> " . htmlspecialchars($row['name']) . "</p>";
        echo "<p><strong>Tickets:</strong> " . $num_tickets . "</p>";
        echo "<p><strong>Total Price:</strong> " . $total_price . " SAR</p>";

        echo "<form method='post' action='cart.php'>";
        echo "<input type='hidden' name='confirm' value='1'>";
        echo "<input type='hidden' name='event_id' value='" . htmlspecialchars($row['id']) . "'>";
        echo "<input type='hidden' name='num_tickets' value='" . htmlspecialchars($num_tickets) . "'>";
        echo "<button type='submit'>Confirm Booking ✅</button>";
        echo "</form>";

    } else {
        echo "<p>Event not found.</p>";
    }
} else {
    echo "<p>Invalid request.</p>";
}
?>
</div>

</body>
</html>
