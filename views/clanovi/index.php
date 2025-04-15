<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db_connection.php';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/controllers/ClanController.php';

requireAdmin();

$clanController = new ClanController($conn);
$current_page = max(1, (int)($_GET['page'] ?? 1));
$per_page = 10;
$clanovi = $clanController->getAllMembers($current_page, $per_page);
$total_pages = ceil($clanController->countMembers() / $per_page); // Sada radi
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Upravljanje članovima</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="mb-0">
                    <i class="bi bi-people-fill"></i> Popis članova
                    <span class="badge bg-light text-dark ms-2"><?= $clanController->countMembers() ?></span>
                </h3>
                <a href="dodaj.php" class="btn btn-light">
                    <i class="bi bi-plus-lg"></i> Novi član
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Ime</th>
                                <th>Prezime</th>
                                <th>Email</th>
                                <th>Uloga</th>
                                <th class="text-end">Akcije</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clanovi as $clan): ?>
                            <tr>
                                <td><?= htmlspecialchars($clan['IDClan']) ?></td>
                                <td><?= htmlspecialchars($clan['Ime']) ?></td>
                                <td><?= htmlspecialchars($clan['Prezime']) ?></td>
                                <td><?= htmlspecialchars($clan['Email']) ?></td>
                                <td>
                                    <span class="badge <?= $clan['role'] === 'admin' ? 'bg-danger' : 'bg-secondary' ?>">
                                        <?= htmlspecialchars($clan['role']) ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <a href="uredi.php?id=<?= $clan['IDClan'] ?>" 
                                           class="btn btn-sm btn-warning"
                                           title="Uredi">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="obrisi.php?id=<?= $clan['IDClan'] ?>" 
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