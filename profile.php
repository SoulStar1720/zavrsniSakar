<?php 
include('includes/auth.php');
include('includes/db_connection.php');
include('includes/header.php');

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

if (isAdmin()) {
    header("Location: admin/index.php");
    exit();
}

// Dohvat podataka iz originalnog index.php
$userId = $_SESSION['user_id'];
$user = [];
$posudbe = [];

// Podaci o korisniku
$stmtUser = $conn->prepare("SELECT * FROM Clan WHERE IDClan = ?");
$stmtUser->bind_param("i", $userId);
$stmtUser->execute();
$user = $stmtUser->get_result()->fetch_assoc();

// Aktivne posudbe
$stmtPosudbe = $conn->prepare("
    SELECT v.naslov, p.DatumPosudbe 
    FROM Posudba pa
    JOIN Primjerak p ON pa.PrimjerakID = p.IDPrimjerak
    JOIN VrstaLiterature v ON p.LiteraturaID = v.IDLiteratura
    WHERE pa.ClanID = ? AND pa.DatumVracanja IS NULL
");
$stmtPosudbe->bind_param("i", $userId);
$stmtPosudbe->execute();
$posudbe = $stmtPosudbe->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Profil korisnika</title>
    <?php include __DIR__ . '/includes/header.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/includes/header.php'; ?>

    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="mb-0">
                    <i class="bi bi-person-circle"></i> 
                    <?= htmlspecialchars($user['Ime'] ?? 'Korisnik') ?>
                    <?= htmlspecialchars($user['Prezime'] ?? '') ?>
                </h3>
                <a href="logout.php" class="btn btn-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Odjava
                </a>
            </div>

            <div class="card-body">
                <!-- Točna kopija originalnog sadržaja -->
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card h-100 border-info">
                            <div class="card-body">
                                <h5><i class="bi bi-info-circle"></i> Vaši podaci</h5>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <strong>Ime:</strong> <?= htmlspecialchars($user['Ime']) ?>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Prezime:</strong> <?= htmlspecialchars($user['Prezime']) ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card h-100 border-warning">
                            <div class="card-body">
                                <h5><i class="bi bi-book"></i> Aktivne posudbe</h5>
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
    </div>
</body>
</html>