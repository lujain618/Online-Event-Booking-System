<?php
// Start session to manage admin login state
session_start();

// Define predefined admin credentials
$admin_username = "admin";
$admin_password = "admin123";

// Initialize login error message
$login_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user inputs
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    // Check if the username and password match the predefined credentials
    if ($username == $admin_username && $password == $admin_password) {
        // Start session and redirect to the admin page
        $_SESSION["loggedin"] = true;
        header("Location: manageEvents.php");
        exit();
    } else {
        // Set error message for invalid credentials
        $login_error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
   
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        
        <?php
        // Display error message if login failed
        if ($login_error != "") {
            echo "<p class='error'>$login_error</p>";
        }
        ?>

        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
