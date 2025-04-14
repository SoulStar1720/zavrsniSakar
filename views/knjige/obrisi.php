<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db_connection.php';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/controllers/KnjigaController.php';

requireAdmin();

$knjigaController = new KnjigaController($conn);
$error = '';

// Dohvat podataka o knjizi
$knjiga = null;
if (isset($_GET['id'])) {
    $knjiga = $knjigaController->getBookById((int)$_GET['id']);
}

if (!$knjiga) {
    $_SESSION['error'] = "Knjiga nije pronađena";
    header("Location: index.php");
    exit();
}

// Glavna logika za brisanje
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($knjigaController->deleteBook($knjiga['IDLiteratura'])) {
            $_SESSION['success'] = "Knjiga uspješno obrisana!";
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
    <title>Obriši knjigu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-danger text-white">
                <h3 class="mb-0">
                    <i class="bi bi-trash"></i> Obriši knjigu
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
                    <h5>Jeste li sigurni da želite obrisati ovu knjigu?</h5>
                    <p class="mb-0">
                        <strong>Naslov:</strong> <?= htmlspecialchars($knjiga['naslov']) ?><br>
                        <strong>Autor:</strong> <?= htmlspecialchars($knjiga['autor']) ?><br>
                        <strong>ISBN:</strong> <?= htmlspecialchars($knjiga['ISBN_broj']) ?>
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
</body>
</html>