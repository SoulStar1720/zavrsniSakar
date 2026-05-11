<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db_connection.php';
require_once __DIR__ . '/../../includes/controllers/PosudbaController.php';

// Dozvoli pristup prijavljenim korisnicima
requireLogin(); 

$posudbaController = new PosudbaController($conn);
$error = '';
$success = false;

// Uzimamo ID knjige iz URL-a
$knjiga_id = isset($_GET['knjiga_id']) ? (int)$_GET['knjiga_id'] : 0;

if ($knjiga_id <= 0) {
    die("Nije odabrana knjiga.");
}

// 1. Dohvaćamo podatke o knjizi i PRVI dostupni primjerak automatski
$sql = "SELECT k.naslov, p.IDPrimjerak 
        FROM knjige k 
        LEFT JOIN primjerak p ON k.IDKnjiga = p.KnjigaID 
        WHERE k.IDKnjiga = ? AND p.status = 'dostupno' 
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $knjiga_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if (!$result) {
    $error = "Nažalost, trenutno nema dostupnih primjeraka ove knjige.";
} else {
    $naslov_knjige = $result['naslov'];
    $automatski_primjerak_id = $result['IDPrimjerak'];
}

// 2. Obrada posudbe na klik gumba
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($automatski_primjerak_id)) {
    try {
        // ID člana je onaj tko je ulogiran
        $clanID = $_SESSION['user_id'];
        
        if ($posudbaController->createLoan($clanID, $automatski_primjerak_id)) {
            echo "<script>alert('Uspješno ste posudili knjigu: $naslov_knjige'); window.location.href='../../index.php';</script>";
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
    <?php include __DIR__ . '/../../includes/header.php'; ?>
    <title>Potvrda posudbe</title>
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow mx-auto" style="max-width: 500px;">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Potvrda posudbe</h4>
            </div>
            <div class="card-body text-center py-4">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                    <a href="../../index.php" class="btn btn-secondary">Povratak</a>
                <?php else: ?>
                    <p class="lead">Želite li posuditi knjigu:</p>
                    <h3 class="text-primary mb-4"><?= htmlspecialchars($naslov_knjige) ?></h3>
                    
                    <form method="POST">
                        <input type="hidden" name="primjerakID" value="<?= $automatski_primjerak_id ?>">
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                Da, posudi odmah
                            </button>
                            <a href="../../index.php" class="btn btn-outline-secondary">Odustani</a>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
            <div class="card-footer text-muted small text-center">
                Prijavljeni ste kao: <?= $_SESSION['user_email'] ?? $_SESSION['email'] ?? 'Korisnik' ?>
            </div>
        </div>
    </div>
</body>
</html>