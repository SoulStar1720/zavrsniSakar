<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db_connection.php';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/controllers/PosudbaController.php';

requireAdmin();

$posudbaController = new PosudbaController($conn);
$error = '';

// Dohvat dostupnih članova i primjeraka
$clanovi = [];
$primjerci = [];
$stmtClanovi = $conn->query("SELECT IDClan, CONCAT(Prezime, ' ', Ime) AS ImePrezime FROM Clan ORDER BY Prezime");
$clanovi = $stmtClanovi->fetch_all(MYSQLI_ASSOC);

$stmtPrimjerci = $conn->query("
    SELECT p.IDPrimjerak, v.naslov 
    FROM Primjerak p
    JOIN VrstaLiterature v ON p.LiteraturaID = v.IDLiteratura
    WHERE p.Dostupno = 'dostupno'
");
$primjerci = $stmtPrimjerci->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($posudbaController->createLoan(
            (int)$_POST['clanID'],
            (int)$_POST['primjerakID']
        )) {
            $_SESSION['success'] = "Knjiga uspješno posuđena!";
            header("Location: index.php");
            exit();
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Nova posudba</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">
                    <i class="bi bi-book"></i> Nova posudba
                    <a href="index.php" class="btn btn-light btn-sm float-end">
                        <i class="bi bi-arrow-left"></i> Natrag
                    </a>
                </h3>
            </div>

            <div class="card-body">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Član</label>
                            <select name="clanID" class="form-select" required>
                                <?php foreach ($clanovi as $clan): ?>
                                    <option value="<?= $clan['IDClan'] ?>">
                                        <?= htmlspecialchars($clan['ImePrezime']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Dostupni primjerci</label>
                            <select name="primjerakID" class="form-select" required>
                                <?php foreach ($primjerci as $primjerak): ?>
                                    <option value="<?= $primjerak['IDPrimjerak'] ?>">
                                        <?= htmlspecialchars($primjerak['naslov']) ?> (ID: <?= $primjerak['IDPrimjerak'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Potvrdi posudbu
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>