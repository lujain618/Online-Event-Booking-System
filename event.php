<!-- event.php -->
<?php
include 'includes/config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Details</title>
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
        label, input, button {
            display: block;
            margin: 10px auto;
            text-align: center;
        }
        input[type="number"] {
            width: 60px;
            padding: 5px;
            font-size: 16px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<div class="container">
    <?php
    if (isset($_GET['id'])) {
        $event_id = $_GET['id'];

        $query = "SELECT * FROM events WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $event_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            ?>
            <h1><?php echo htmlspecialchars($row['name']); ?></h1>
            <p><strong>Date:</strong> <?php echo htmlspecialchars($row['date_time']); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
            <p><strong>Price:</strong> <?php echo htmlspecialchars($row['price']); ?> SAR</p>
            <p><strong>Available Tickets:</strong> <?php echo htmlspecialchars($row['max_tickets']); ?></p>

            <form action="cart.php" method="post">
                <input type="hidden" name="event_id" value="<?php echo $row['id']; ?>">
                <label for="num_tickets">Number of Tickets:</label>
                <input type="number" id="num_tickets" name="num_tickets" min="1" max="<?php echo $row['max_tickets']; ?>" required>
                <button type="submit">Add to Cart 🎒</button>
            </form>
            <?php
        } else {
            echo "<p>No event found.</p>";
        }
    } else {
        echo "<p>No event selected.</p>";
    }
    ?>
</div>
</body>
</html>
