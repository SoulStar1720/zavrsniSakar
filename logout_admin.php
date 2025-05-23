<?php
require_once __DIR__ . '/includes/auth.php';

// Uništi sve session varijable
$_SESSION = array();

// Uništi session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Uništi session
session_destroy();

// Preusmjeri na login stranicu
header("Location: login.php");
exit();
?>