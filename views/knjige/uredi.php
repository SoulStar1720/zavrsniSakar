<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db_connection.php';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/controllers/KnjigaController.php';

requireAdmin();

$knjigaController = new KnjigaController($conn);
$error = '';

// Lista vrsta literature
$vrste_literature = [
    'Udžbenik',
    'Fakultativna knjiga',
    'Znanstveni časopis',
    'Stručna literatura'
];

// Dohvat postojećih podataka
$knjiga = null;
if (isset($_GET['id'])) {
    $knjiga = $knjigaController->getBookById((int)$_GET['id']);
}

if (!$knjiga) {
    $_SESSION['error'] = "Knjiga nije pronađena";
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $podaci = [
        'naslov' => trim($_POST['naslov']),
        'autor' => trim($_POST['autor']),
        'isbn' => trim($_POST['isbn']),
        'izdavac' => trim($_POST['izdavac']),
        'vrsta' => $_POST['vrsta'],
        'broj_primjeraka' => (int)$_POST['broj_primjeraka'],
        'id' => $knjiga['IDLiteratura']
    ];

    try {
        if ($knjigaController->updateBook($knjiga['IDLiteratura'], $podaci)) {
            $_SESSION['success'] = "Knjiga uspješno ažurirana!";
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
    <title>Uredi knjigu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">
                    <i class="bi bi-pencil-square"></i> Uredi knjigu
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
                        <div class="col-12">
                            <label class="form-label">Naslov knjige</label>
                            <input type="text" name="naslov" class="form-control" 
                                   value="<?= htmlspecialchars($knjiga['naslov']) ?>" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Autor</label>
                            <input type="text" name="autor" class="form-control"
                                   value="<?= htmlspecialchars($knjiga['autor']) ?>" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">ISBN broj</label>
                            <input type="text" name="isbn" class="form-control"
                                   value="<?= htmlspecialchars($knjiga['ISBN_broj']) ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Izdavač</label>
                            <input type="text" name="izdavac" class="form-control"
                                   value="<?= htmlspecialchars($knjiga['izdavac']) ?>" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Vrsta literature</label>
                            <select name="vrsta" class="form-select" required>
                                <?php foreach ($vrste_literature as $vrsta): ?>
                                    <option value="<?= htmlspecialchars($vrsta) ?>" 
                                        <?= $vrsta === $knjiga['vrsta_literature'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($vrsta) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Broj primjeraka</label>
                            <input type="number" name="broj_primjeraka" class="form-control" 
                                   min="1" value="<?= htmlspecialchars($knjiga['broj_primjeraka']) ?>" required>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Spremi promjene
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>