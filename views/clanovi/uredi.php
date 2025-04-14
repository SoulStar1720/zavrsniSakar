<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db_connection.php';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/controllers/ClanController.php';

requireAdmin();

$clanController = new ClanController($conn);
$error = '';

$clan = null;
if (isset($_GET['id'])) {
    $clan = $clanController->getMemberById((int)$_GET['id']);
}

if (!$clan) {
    $_SESSION['error'] = "Član nije pronađen";
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $podaci = [
        'ime' => trim($_POST['ime']),
        'prezime' => trim($_POST['prezime']),
        'email' => trim($_POST['email']),
        'role' => $_POST['role'] ?? 'user',
        'id' => $clan['IDClan']
    ];

    try {
        if ($clanController->updateMember($clan['IDClan'], $podaci)) {
            $_SESSION['success'] = "Član uspješno ažuriran!";
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
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">
                <i class="bi bi-pencil-square"></i> Uredi člana
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
                        <label class="form-label">Ime</label>
                        <input type="text" name="ime" class="form-control" 
                               value="<?= htmlspecialchars($clan['Ime']) ?>" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Prezime</label>
                        <input type="text" name="prezime" class="form-control"
                               value="<?= htmlspecialchars($clan['Prezime']) ?>" required>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                               value="<?= htmlspecialchars($clan['Email']) ?>" required>
                    </div>
                    
                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                    <div class="col-md-6">
                        <label class="form-label">Uloga</label>
                        <select name="role" class="form-select">
                            <option value="user" <?= $clan['role'] === 'user' ? 'selected' : '' ?>>Obični korisnik</option>
                            <option value="admin" <?= $clan['role'] === 'admin' ? 'selected' : '' ?>>Administrator</option>
                        </select>
                    </div>
                    <?php endif; ?>
                    
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Ažuriraj
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>