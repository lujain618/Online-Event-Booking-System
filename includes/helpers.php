<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function redirect($url) {
    header("Location: " . $url);
    exit();
}

function checkAdminSession() {
    if (!isset($_SESSION['admin_logged_in'])) {
        redirect("admin.php");
    }
}

function checkCustomerSession() {
    if (!isset($_SESSION['user_id'])) {
        redirect("index.php");
    }
}
?>
