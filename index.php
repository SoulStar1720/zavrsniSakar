<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db_connection.php';

// Pokretanje sesije ako već nije pokrenuta
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirekcija za admine
if (isLoggedIn() && isAdmin()) {
    header("Location: views/admin/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sustav za knjižnicu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .auth-container { min-height: 100vh; display: flex; align-items: center; }
        .dashboard-card { width: 100%; max-width: 800px; margin: 0 auto; }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="container">
            <?php if (isLoggedIn()): ?>
                <!-- PRIJAVLJENI KORISNIK -->
                <div class="card shadow dashboard-card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">
                            <i class="bi bi-person-circle"></i> Dobrodošli, 
                            <?= htmlspecialchars($_SESSION['user_ime'] ?? 'Korisnik') ?>
                            <a href="logout.php" class="btn btn-light btn-sm float-end">
                                <i class="bi bi-box-arrow-right"></i> Odjava
                            </a>
                        </h3>
                    </div>

                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="card h-100 border-info">
                                    <div class="card-body">
                                        <h5><i class="bi bi-info-circle"></i> Vaši podaci</h5>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">
                                                <strong>Ime:</strong> <?= htmlspecialchars($_SESSION['user_ime'] ?? 'N/A') ?>
                                            </li>
                                            <li class="list-group-item">
                                                <strong>Prezime:</strong> <?= htmlspecialchars($_SESSION['user_prezime'] ?? 'N/A') ?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card h-100 border-warning">
                                    <div class="card-body">
                                        <h5><i class="bi bi-book"></i> Aktivne posudbe</h5>
                                        <?php
                                        $posudbe = [];
                                        if (isset($_SESSION['user_id'])) {
                                            $stmt = $conn->prepare("
                                                SELECT v.naslov, p.DatumPosudbe 
                                                FROM Posudba p
                                                JOIN Primjerak pr ON p.PrimjerakID = pr.IDPrimjerak
                                                JOIN VrstaLiterature v ON pr.LiteraturaID = v.IDLiteratura
                                                WHERE p.ClanID = ? AND p.DatumVracanja IS NULL
                                            ");
                                            $stmt->bind_param("i", $_SESSION['user_id']);
                                            $stmt->execute();
                                            $posudbe = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                                        }
                                        ?>
                                        
                                        <?php if (!empty($posudbe)): ?>
                                            <div class="list-group">
                                                <?php foreach ($posudbe as $posudba): ?>
                                                    <div class="list-group-item">
                                                        <?= htmlspecialchars($posudba['naslov']) ?>
                                                        <small class="text-muted">(<?= $posudba['DatumPosudbe'] ?>)</small>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="alert alert-info mb-0">Nema aktivnih posudbi</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <!-- NEPRIJAVLJENI KORISNICI -->
                <div class="card shadow" style="max-width: 500px; margin: 0 auto;">
                    <div class="card-header bg-primary text-white text-center">
                        <h3 class="mb-0"><i class="bi bi-book"></i> Dobrodošli</h3>
                    </div>
                    
                    <div class="card-body text-center">
                        <div class="d-grid gap-3">
                            <a href="login.php" class="btn btn-lg btn-success">
                                <i class="bi bi-box-arrow-in-right"></i> Prijava
                            </a>
                            
                            <a href="register.php" class="btn btn-lg btn-primary">
                                <i class="bi bi-person-plus"></i> Registracija
                            </a>
                        </div>
                        
                        <div class="mt-4 text-muted">
                            <small>Sustav za upravljanje knjižnicom</small>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>