<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db_connection.php';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/controllers/ClanController.php';

requireAdmin();

$clanController = new ClanController($conn);
$error = '';

// Provjera ID-a i dohvat člana
$clan = null;
if (isset($_GET['id'])) {
    $clan = $clanController->getMemberById((int)$_GET['id']);
}

if (!$clan) {
    $_SESSION['error'] = "Član nije pronađen";
    header("Location: index.php");
    exit();
}

// Glavna logika za brisanje
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($clanController->deleteMember($clan['IDClan'])) {
            $_SESSION['success'] = "Član uspješno obrisan!";
            header("Location: index.php");
            exit();
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-danger text-white">
            <h3 class="mb-0">
                <i class="bi bi-trash"></i> Obriši člana
                <a href="index.php" class="btn btn-light btn-sm float-end">
                    <i class="bi bi-arrow-left"></i> Natrag
                </a>
            </h3>
        </div>

        <div class="card-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="alert alert-warning">
                <h5>Jeste li sigurni da želite obrisati ovog člana?</h5>
                <p class="mb-0">
                    <strong>Ime:</strong> <?= htmlspecialchars($clan['Ime']) ?><br>
                    <strong>Prezime:</strong> <?= htmlspecialchars($clan['Prezime']) ?><br>
                    <strong>Email:</strong> <?= htmlspecialchars($clan['Email']) ?>
                </p>
            </div>

            <form method="POST">
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Trajno obriši
                    </button>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Odustani
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>