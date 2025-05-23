<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db_connection.php';

if (isLoggedIn()) {
    redirectBasedOnRole();
    exit();
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <?php include __DIR__ . '/includes/header.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/includes/header.php'; ?>
    
    <main class="container py-5 text-center">
        <h1 class="display-4 mb-4">Dobrodošli u knjižnicu</h1>
        <p class="lead mb-5">Pregledajte našu kolekciju ili se prijavite</p>
        
        <div class="d-flex justify-content-center gap-3">
            <a href="login.php" class="btn btn-primary btn-lg">Prijava</a>
            <a href="register.php" class="btn btn-outline-primary btn-lg">Registracija</a>
        </div>
    </main>
</body>
</html>