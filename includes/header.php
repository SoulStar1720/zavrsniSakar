<?php
// Pokreni session samo ako već nije pokrenuta
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 86400, // 24h
        'cookie_secure'   => !empty($_SERVER['HTTPS']),
        'cookie_httponly' => true,
        'use_strict_mode' => true
    ]);
}

// Sigurnosni headeri
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sustav za knjižnicu</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        .navbar-brand {
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .nav-link {
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            transform: translateY(-2px);
        }
        .user-role-badge {
            background: rgba(255,255,255,0.1);
            padding: 5px 15px;
            border-radius: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="/zavrsniSakar/index.php">
                <i class="bi bi-book-half me-2"></i>Knjižnica
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
           
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto">
                  
                </ul> 
                
                <div class="d-flex align-items-center">
                    <?php if (isLoggedIn()): ?>
                        <div class="text-light me-3">
                            <i class="bi bi-person-circle me-2"></i>
                            <span class="user-role-badge">
                                <?= strtoupper($_SESSION['user_role'] ?? 'korisnik') ?>
                            </span>
                        </div>
                        <a href="/zavrsniSakar/logout.php" class="btn btn-outline-light">
                            <i class="bi bi-box-arrow-right me-1"></i>Odjava
                        </a>
                    <?php else: ?>
                        <a href="/zavrsniSakar/login.php" class="btn btn-outline-light me-2">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Prijava
                        </a>
                        <a href="/zavrsniSakar/register.php" class="btn btn-light">
                            <i class="bi bi-person-plus me-1"></i>Registracija
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <div class="container mt-4">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>