<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db_connection.php';
require_once __DIR__ . '/../../includes/header_admin.php';
require_once __DIR__ . '/../../includes/controllers/PosudbaController.php';

requireAdmin();

$posudbaController = new PosudbaController($conn);
$posudbe = $posudbaController->getAllLoans();
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Upravljanje posudbama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="mb-0">
                    <i class="bi bi-arrow-left-right"></i> Popis posudbi
                </h3>
                <a href="posudi.php" class="btn btn-light">
                    <i class="bi bi-plus-lg"></i> Nova posudba
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Član</th>
                                <th>Knjiga</th>
                                <th>Datum posudbe</th>
                                <th>Status</th>
                                <th class="text-end">Akcije</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($posudbe as $posudba): ?>
                            <tr>
                                <td><?= htmlspecialchars($posudba['Prezime'] . ' ' . htmlspecialchars($posudba['Ime'])) ?></td>
                                <td>
                                    <?= htmlspecialchars($posudba['naslov']) ?> 
                                    (ID: <?= htmlspecialchars($posudba['IDPrimjerak']) ?>)
                                </td>
                                <td><?= htmlspecialchars($posudba['DatumPosudbe']) ?></td>
                                <td>
                                    <span class="badge <?= $posudba['DatumVracanja'] ? 'bg-success' : 'bg-warning' ?>">
                                        <?= $posudba['DatumVracanja'] ? 'Vraćeno' : 'Aktivno' ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <?php if (!$posudba['DatumVracanja']): ?>
                                    <a href="vrati.php?id=<?= $posudba['PosudbaID'] ?>" 
                                       class="btn btn-sm btn-success"
                                       title="Vrati knjigu">
                                        <i class="bi bi-arrow-return-left"></i>
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>