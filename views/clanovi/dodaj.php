<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db_connection.php';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/controllers/ClanController.php';

requireAdmin();

$clanController = new ClanController($conn);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $podaci = [
        'ime' => trim($_POST['ime']),
        'prezime' => trim($_POST['prezime']),
        'email' => trim($_POST['email']),
        'lozinka' => trim($_POST['lozinka']),
        'tip' => $_POST['tip'],
        'role' => $_POST['role'] ?? 'user'
    ];

    try {
        if ($clanController->addMember($podaci)) {
            $_SESSION['success'] = "Član uspješno dodan!";
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
                <i class="bi bi-person-plus"></i> Dodaj novog člana
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
                        <input type="text" name="ime" class="form-control" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Prezime</label>
                        <input type="text" name="prezime" class="form-control" required>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label">Lozinka</label>
                        <input type="password" name="lozinka" class="form-control" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Tip člana</label>
                        <select name="tip" class="form-select" required>
                            <option value="student">Student</option>
                            <option value="profesor">Profesor</option>
                        </select>
                    </div>
                    
                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                    <div class="col-md-6">
                        <label class="form-label">Uloga</label>
                        <select name="role" class="form-select">
                            <option value="user">Obični korisnik</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </div>
                    <?php endif; ?>
                    
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Spremi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>