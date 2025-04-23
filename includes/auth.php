<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
/*
// Sigurnosne postavke za sesije
//Ovo pa sve nadalje su neeke sigurnosti ko za pravu stranicu 
ini_set('session.cookie_httponly', 1); //Onemogućava JavaScriptu pristup session cookie-u (npr. document.cookie neće vidjeti cookie).
ini_set('session.cookie_secure', 1); // Koristi samo ako je HTTPS //Šalje cookie samo preko HTTPS veze (ignorira ga na HTTP).
ini_set('session.use_strict_mode', 1); //Prihvaća samo unaprijed inicirane ID-eve sesija.

// Zaštita od session fixation
if (!isset($_SESSION['initiated'])) {  //Generira novi ID sesije pri svakoj prijavi i briše stari.
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

// Zaštita od XSS (Cross-Site Scripting) u ispisu podataka iz sesije
function escapeSessionData($data) {                             //Sprječava XSS napade ako bi napadač uspio ubaciti štetni kod u podatke sesije.
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}*/
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
    if (!isAdmin()) {
        header("HTTP/1.1 403 Forbidden");
        die("Nemate ovlaštenje za pristup ovoj stranici");
    }
}
/*služi za zaštitu od CSRF napada (Cross-Site Request Forgery).
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCsrfToken($token) {
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}*/
?>