<?php
session_start();
include 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        if ($stmt->rowCount() > 0) {
            $error = "Email is already registered.";
        } else {
            // Insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $hashed_password
            ]);

            // Auto-login
            $_SESSION['user_id'] = $conn->lastInsertId();
            $_SESSION['user_name'] = $name;

            redirect('home.php');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Event Booking System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f7f3;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register-container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0,0,0,0.1);
            width: 400px;
            text-align: center;
        }

        h2 {
            color: #2E8659;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        label {
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
            font-size: 14px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            margin-bottom: 15px;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            padding: 12px;
            width: 100%;
            background-color: #3AA46F;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            background-color: #2E8659;
        }

        .login-link {
            margin-top: 20px;
            font-size: 14px;
            text-align: center;
            width: 100%;
        }

        .login-link a {
            color: #2E8659;
            text-decoration: none;
            font-weight: bold;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            margin-bottom: 15px;
            font-weight: bold;
            text-align: center;
            width: 100%;
        }
    </style>
</head>
<body>

<div class="register-container">
    <h2>Customer Registration</h2>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <label for="name">Full Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <input type="submit" value="Register">
    </form>

    <div class="login-link">
        Already have an account? <a href="index.php">Login here</a>
    </div>
</div>

</body>
</html>
