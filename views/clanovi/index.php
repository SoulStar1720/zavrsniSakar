<?php
require_once 'includes/db_connection.php';
require_once 'includes/auth.php';
require_once 'includes/controllers/ClanController.php';

$current_page = max(1, (int)($_GET['page'] ?? 1));
$per_page = 10;

$clanController = new ClanController($conn);
$clanovi = $clanController->getAllMembers($current_page, $per_page);
$total_pages = ceil($clanController->countMembers() / $per_page);

require_once 'includes/header.php';
?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">
                <i class="bi bi-people-fill"></i> 
                Popis članova
                <span class="badge bg-light text-dark float-end">Ukupno: <?= $clanController->countMembers() ?></span>
            </h3>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Ime</th>
                            <th scope="col">Prezime</th>
                            <th scope="col">Tip</th>
                            <th scope="col">Email</th>
                            <th scope="col">Uloga</th>
                            <th scope="col" class="text-end">Akcije</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clanovi as $clan): ?>
                        <tr>
                            <th scope="row"><?= htmlspecialchars($clan['IDClan']) ?></th>
                            <td><?= htmlspecialchars($clan['Ime']) ?></td>
                            <td><?= htmlspecialchars($clan['Prezime']) ?></td>
                            <td>
                                <span class="badge bg-info">
                                    <?= htmlspecialchars($clan['Tip']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="mailto:<?= htmlspecialchars($clan['Email']) ?>" class="text-decoration-none">
                                    <?= htmlspecialchars($clan['Email']) ?>
                                </a>
                            </td>
                            <td>
                                <span class="badge <?= $clan['role'] === 'admin' ? 'bg-danger' : 'bg-secondary' ?>">
                                    <?= htmlspecialchars($clan['role']) ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="clanovi/uredi.php?id=<?= $clan['IDClan'] ?>" 
                                       class="btn btn-sm btn-outline-warning"
                                       data-bs-toggle="tooltip" 
                                       title="Uredi člana">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal<?= $clan['IDClan'] ?>"
                                            title="Obriši člana">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($total_pages > 1): ?>
            <nav aria-label="Navigacija stranicama">
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

<?php 
// Dodajte Bootstrap JS i povezane skripte u footer.php
require_once 'includes/footer.php';
?>