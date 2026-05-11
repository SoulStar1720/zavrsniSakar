<?php 
include('includes/auth.php');
include('includes/db_connection.php');
include('includes/header.php');

// Provjera prijave
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Admini imaju svoj panel
if (isAdmin()) {
    header("Location: views/admin/index.php");
    exit();
}

$userId = $_SESSION['user_id'];

// 1. Dohvat podataka o članu
$stmtUser = $conn->prepare("SELECT * FROM clan WHERE IDClan = ?");
$stmtUser->bind_param("i", $userId);
$stmtUser->execute();
$user = $stmtUser->get_result()->fetch_assoc();

// 2. Dohvat aktivnih posudbi (Knjige koje su trenutno kod korisnika)
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

// 3. Dohvat aktivnih rezervacija
$stmtRezervacije = $conn->prepare("
    SELECT r.IDRezervacija, k.naslov, r.DatumRezervacije, r.Status 
    FROM rezervacija r
    JOIN knjige k ON r.KnjigaID = k.IDKnjiga
    WHERE r.ClanID = ? AND r.Status = 'Aktivna'
");
$stmtRezervacije->bind_param("i", $userId);
$stmtRezervacije->execute();
$rezervacije = $stmtRezervacije->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Moj Profil - Knjižnica</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <div class="d-flex align-items-center mb-5">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                        <i class="bi bi-person-badge fs-1"></i>
                    </div>
                    <div class="ms-3">
                        <h1 class="h2 mb-0"><?= htmlspecialchars($user['Ime'] . " " . $user['Prezime']) ?></h1>
                        <p class="text-muted mb-0"><?= $_SESSION['user_email'] ?? $_SESSION['email'] ?? 'Korisnički račun' ?></p>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0 bg-light">
                            <div class="card-body">
                                <h5 class="card-title fw-bold mb-3">Osobni podaci</h5>
                                <hr>
                                <p class="mb-1 text-muted small">Ime i prezime:</p>
                                <p class="fw-bold"><?= htmlspecialchars($user['Ime'] . " " . $user['Prezime']) ?></p>
                                <p class="mb-1 text-muted small">ID Člana:</p>
                                <p class="fw-bold">#<?= $userId ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card h-100 shadow-sm border-primary">
                            <div class="card-body">
                                <h5 class="card-title text-primary fw-bold mb-3">
                                    <i class="bi bi-book"></i> Knjige kod mene
                                </h5>
                                <?php if (!empty($posudbe)): ?>
                                    <div class="list-group list-group-flush">
                                        <?php foreach ($posudbe as $pos): ?>
                                            <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span class="d-block fw-bold"><?= htmlspecialchars($pos['naslov']) ?></span>
                                                    <small class="text-muted">Posuđeno: <?= date('d.m.Y.', strtotime($pos['DatumPosudbe'])) ?></small>
                                                </div>
                                                <form action="views/posudbe/vrati.php" method="POST" onsubmit="return confirm('Sigurno želite vratiti ovu knjigu?');">
                                                    <input type="hidden" name="posudba_id" value="<?= $pos['IDPosudba'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger">Vrati knjigu</button>
                                                </form>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted py-3">Trenutno nemate zaduženih knjiga.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-4">
                        <div class="card shadow-sm border-info">
                            <div class="card-body">
                                <h5 class="card-title text-info fw-bold mb-3">
                                    <i class="bi bi-bookmark-heart"></i> Moje rezervacije
                                </h5>
                                <?php if (!empty($rezervacije)): ?>
                                    <div class="table-responsive">
                                        <table class="table align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Naslov knjige</th>
                                                    <th>Datum</th>
                                                    <th>Status</th>
                                                    <th class="text-end">Akcija</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($rezervacije as $rez): ?>
                                                    <tr>
                                                        <td><strong><?= htmlspecialchars($rez['naslov']) ?></strong></td>
                                                        <td><?= date('d.m.Y.', strtotime($rez['DatumRezervacije'])) ?></td>
                                                        <td><span class="badge bg-info text-dark">Aktivna</span></td>
                                                        <td class="text-end">
                                                            <a href="otkazi_rezervaciju.php?id=<?= $rez['IDRezervacija'] ?>" 
                                                               class="btn btn-sm btn-outline-secondary" 
                                                               onclick="return confirm('Otkaži rezervaciju?')">Otkaži</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted">Nemate aktivnih rezervacija.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 border-top pt-4 text-center">
                    <a href="index.php" class="btn btn-primary px-5 py-2 me-2">
                        <i class="bi bi-house"></i> Povratak na početnu
                    </a>
                    <a href="logout.php" class="btn btn-outline-danger px-4">Odjava</a>
                </div>

            </div>
        </div>
    </div>
</body>
</html>