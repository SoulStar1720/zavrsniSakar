<?php 
include('includes/auth.php');
include('includes/db_connection.php');
include('includes/header.php');

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

if (isAdmin()) {
    header("Location: views/admin/index.php");
    exit();
}

$userId = $_SESSION['user_id'];
$user = [];
$posudbe = [];

// 1. Podaci o korisniku
$stmtUser = $conn->prepare("SELECT * FROM clan WHERE IDClan = ?");
$stmtUser->bind_param("i", $userId);
$stmtUser->execute();
$user = $stmtUser->get_result()->fetch_assoc();

// 2. Aktivne posudbe - ISPRAVLJEN SQL (Dodan pa.IDPosudba i k.naslov)
$stmtPosudbe = $conn->prepare("
    SELECT pa.IDPosudba, k.naslov, pa.DatumPosudbe 
    FROM posudba pa
    JOIN primjerak p ON pa.PrimjerakID = p.IDPrimjerak
    JOIN knjige k ON p.KnjigaID = k.IDKnjiga
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
    <title>Moj Profil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-person-fill fs-2"></i>
                    </div>
                    <div class="ms-3">
                        <h2 class="mb-0">Dobrodošli, <?= htmlspecialchars($user['Ime'] ?? 'Korisniče') ?>!</h2>
                        <p class="text-muted mb-0"><?= $_SESSION['user_email'] ?? $_SESSION['email'] ?? 'Korisnik' ?></p>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-5">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title border-bottom pb-2 mb-3">Osobni podaci</h5>
                                <p><strong>Ime:</strong> <?= htmlspecialchars($user['Ime'] ?? '/') ?></p>
                                <p><strong>Prezime:</strong> <?= htmlspecialchars($user['Prezime'] ?? '/') ?></p>
                                <p><strong>ID Člana:</strong> #<?= $userId ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="card h-100 shadow-sm border-primary">
                            <div class="card-body">
                                <h5 class="card-title border-bottom pb-2 mb-3 text-primary">
                                    <i class="bi bi-book"></i> Moje aktivne posudbe
                                </h5>
                                
                                <?php if (!empty($posudbe)): ?>
                                    <div class="list-group list-group-flush">
                                        <?php foreach ($posudbe as $posudba): ?>
                                            <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="fw-bold"><?= htmlspecialchars($posudba['naslov']) ?></div>
                                                    <small class="text-muted">
                                                        Posuđeno: <?= date('d.m.Y.', strtotime($posudba['DatumPosudbe'])) ?>
                                                    </small>
                                                </div>
                                                
                                                <form action="views/posudbe/vrati.php" method="POST" onsubmit="return confirm('Sigurno želite vratiti ovu knjigu?');">
                                                    <input type="hidden" name="posudba_id" value="<?= $posudba['IDPosudba'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-arrow-left-right"></i> Vrati
                                                    </button>
                                                </form>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-4 text-muted">
                                        <i class="bi bi-journal-x fs-1"></i>
                                        <p>Trenutno nemate zaduženih knjiga.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mt-5 text-center">
                    <a href="index.php" class="btn btn-secondary px-4">
                        <i class="bi bi-house-door"></i> Povratak na početnu
                    </a>
                </div>
            </div> 
        </div>
    </div>
</body>
</html>