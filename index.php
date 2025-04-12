<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/header.php';

if (isLoggedIn()) {
    header("Location: clanovi/");
    exit();
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h2 class="text-center mb-0">
                        <i class="bi bi-book"></i> Dobrodošli
                    </h2>
                </div>
                
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="login.php" class="btn btn-lg btn-success">
                            <i class="bi bi-box-arrow-in-right"></i> Prijava
                        </a>
                        
                        <a href="register.php" class="btn btn-lg btn-primary">
                            <i class="bi bi-person-plus"></i> Registracija
                        </a>
                    </div>

                    <div class="mt-4 text-center text-muted">
                        <small>Sustav za upravljanje knjižnicom</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>