<?php
// Zaštita od duplog uključivanja
if (!defined('HEADER_INCLUDED')) {
    define('HEADER_INCLUDED', true);

    // Session i sigurnosne postavke
    if (session_status() === PHP_SESSION_NONE) {
        session_start([
            'cookie_lifetime' => 86400,
            'cookie_secure' => !empty($_SERVER['HTTPS']),
            'cookie_httponly' => true,
            'use_strict_mode' => true
        ]);
    }

    ob_start(); // Output buffering
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sustav za knjižnicu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .navbar-brand {
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .custom-search {
            position: relative;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }
        .custom-search-input {
            width: 100%;
            padding: 10px 15px;
            border-radius: 25px;
            border: 2px solid #dee2e6;
            outline: none;
            transition: all 0.3s;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-book-half me-2"></i>Knjižnica
            </a>
            <div class="d-flex align-items-center">
                <div class="custom-search me-3">
                    <form action="search.php" method="GET">
                        <input type="search" name="query" class="custom-search-input" placeholder="Pretraži...">
                        <button type="submit" class="btn btn-link position-absolute end-0 top-50 translate-middle-y">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                <?php if (isLoggedIn()): ?>
                    <a href="../../logout_admin.php" class="btn btn-outline-light">
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="d-none d-md-inline">Odjava</span>
                    </a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-light me-2">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span class="d-none d-md-inline">Prijava</span>
                    </a>
                    <a href="register.php" class="btn btn-light">
                        <i class="bi bi-person-plus"></i>
                        <span class="d-none d-md-inline">Registracija</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <main class="container py-4">
        <?php ob_end_flush(); ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
<?php } // Zatvaranje if(!defined()) bloka ?>