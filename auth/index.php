<?php
require_once '../includes/config.php';
require_once '../includes/helpers.php'; 
// If customer is already logged in, redirect to home
if (isset($_SESSION['user_id'])) {
    redirect('home.php');
} 

// Handle login
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']); // Now using email
    $password = trim($_POST['password']);

    // Check in database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        redirect('../customer/home.php');
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Booking System - Customer Login</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f7f3;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .login-container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
            width: 400px;
            text-align: center;
        }

        h1 {
            color: #3AA46F;
            margin-bottom: 10px;
        }

        h2 {
            color: #2E8659;
            margin-bottom: 20px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #3AA46F;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #2E8659;
        }

        .error {
            color: red;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .register-link {
            margin-top: 15px;
            display: block;
            color: #3AA46F;
            font-weight: bold;
            text-decoration: underline;
        }

        .register-link:hover {
            color: #2E8659;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h1>Event Booking System</h1>
    <h2>Customer Login</h2>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" value="Login">
    </form>

    <a class="register-link" href="register.php">Not a member yet? Register here</a>
</div>

</body>
</html>