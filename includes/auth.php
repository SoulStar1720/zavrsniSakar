<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header("HTTP/1.1 403 Forbidden");
        die("Nemate ovlaštenje za pristup ovoj stranici");
    }
}

function redirectBasedOnRole() {
    if (isAdmin()) {
        header("Location: views/admin/index.php");
    } else {
        header("Location: index.php"); // Promijenjeno s profile.php
    }
    exit();
}
?>
