<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['user'])) {
    header('Location: login-form.php');
    exit();
}

// Authorization check
if (isset($requiredRoles) && is_array($requiredRoles)) {
    $userRole = $_SESSION['role'] ?? '';
    if (!in_array($userRole, $requiredRoles)) {
        header('Location: unauthorized.php');
        exit();
    }
}
?>