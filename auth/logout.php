<?php
require_once '../includes/config.php';
require_once '../includes/helpers.php';
// Destroy the session
session_unset();
session_destroy();

// Redirect based on who was logged in
if (isset($_SESSION['admin_logged_in'])) {
    // If admin was logged in (but technically session is already destroyed, so this check won't work here)
    redirect('admin.php');
} else {
    // Otherwise, assume it was a customer
    redirect('index.php');
}
?>
