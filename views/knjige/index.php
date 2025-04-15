<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db_connection.php';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/controllers/KnjigaController.php';

requireAdmin();

$knjigaController = new KnjigaController($conn);
$current_page = max(1, (int)($_GET['page'] ?? 1));
$per_page = 10;
$knjige = $knjigaController->getAllBooks($current_page, $per_page);
$total_pages = ceil($knjigaController->countBooks() / $per_page);
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Upravljanje knjigama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="mb-0">
                    <i class="bi bi-book"></i> Popis knjiga
                    <span class="badge bg-light text-dark ms-2"><?= $knjigaController->countBooks() ?></span>
                </h3>
                <a href="dodaj.php" class="btn btn-light">
                    <i class="bi bi-plus-lg"></i> Nova knjiga
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Naslov</th>
                                <th>Autor</th>
                                <th>ISBN</th>
                                <th>Izdavač</th>
                                <th>Primjeraka</th>
                                <th class="text-end">Akcije</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($knjige as $knjiga): ?>
                            <tr>
                                <td><?= htmlspecialchars($knjiga['IDLiteratura']) ?></td>
                                <td><?= htmlspecialchars($knjiga['naslov']) ?></td>
                                <td><?= htmlspecialchars($knjiga['autor']) ?></td>
                                <td><?= htmlspecialchars($knjiga['ISBN_broj'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($knjiga['izdavac']) ?></td>
                                <td><?= htmlspecialchars($knjiga['broj_primjeraka']) ?></td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <a href="uredi.php?id=<?= $knjiga['IDLiteratura'] ?>" 
                                           class="btn btn-sm btn-warning"
                                           title="Uredi">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="obrisi.php?id=<?= $knjiga['IDLiteratura'] ?>" 
                                           class="btn btn-sm btn-danger"
                                           title="Obriši"
                                           onclick="return confirm('Jeste li sigurni?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($total_pages > 1): ?>
                <nav aria-label="Navigacija">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i === $current_page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>