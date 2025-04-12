<?php
ob_start();
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
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar-brand {
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .alert {
            margin-top: 1rem;
        }
        .main-content {
            flex: 1;
            padding: 2rem 0;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <i class="bi bi-book-half"></i> Knjižnica
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto">
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../clanovi/">
                                <i class="bi bi-people"></i> Članovi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../knjige/">
                                <i class="bi bi-book"></i> Knjige
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../posudbe/">
                                <i class="bi bi-arrow-left-right"></i> Posudbe
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
                
                <div class="d-flex">
                    <?php if (isLoggedIn()): ?>
                        <div class="navbar-text text-light me-3">
                            <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['user_role'] ?? 'korisnik') ?>
                        </div>
                        <a href="../logout.php" class="btn btn-outline-light">
                            <i class="bi bi-box-arrow-right"></i> Odjava
                        </a>
                    <?php else: ?>
                        <a href="../login.php" class="btn btn-outline-light me-2">
                            <i class="bi bi-box-arrow-in-right"></i> Prijava
                        </a>
                        <a href="../register.php" class="btn btn-light">
                            <i class="bi bi-person-plus"></i> Registracija
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <div class="container">
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