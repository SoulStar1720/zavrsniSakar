<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/controllers/KnjigaController.php';

$knjigaController = new KnjigaController($conn);
$knjige = $knjigaController->getAllBooks(1, 20);
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <?php include __DIR__ . '/includes/header.php'; ?>
    <style>
        .book-card { transition: transform 0.2s; border: none; }
        .book-card:hover { transform: translateY(-5px); }
        .card-img-top { height: 350px; object-fit: cover; border-radius: 8px; }
    </style>
</head>
<body>
    <main class="container py-5">
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold">Knjižnica</h1>
            <p class="lead text-muted">Dobrodošli u naš sustav za upravljanje knjigama</p>
            
            <?php if (!isLoggedIn()): ?>
                <div class="mt-4">
                    <a href="login.php" class="btn btn-primary px-4">Prijava</a>
                    <a href="register.php" class="btn btn-outline-primary px-4">Registracija</a>
                </div>
            <?php else: ?>
                <div class="alert alert-info d-inline-block px-4">
                    Prijavljeni ste kao: <strong><?php echo $_SESSION['user_email'] ?? 'Korisnik'; ?></strong>
                </div>
            <?php endif; ?>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php foreach ($knjige as $knjiga): 
                $imeSlike = basename($knjiga['naslovnica']);
                $putanjaSlike = "naslovnice/" . $imeSlike;
                if (empty($imeSlike) || !file_exists(__DIR__ . "/" . $putanjaSlike)) {
                    $putanjaSlike = "https://via.placeholder.com/300x450?text=Nema+Slike";
                }

                // POSTAVLJANJE POVEZNICA OVISNO O STATUSU PRIJAVE
                if (isLoggedIn()) {
                    $detaljiUrl = "detalji_knjige.php?id=" . $knjiga['IDVrsta'];
                    $posudiUrl = "views/posudbe/posudi.php?knjiga_id=" . $knjiga['IDVrsta'];
                    $rezervirajUrl = "rezervacija.php?id=" . $knjiga['IDVrsta'];
                } else {
                    // Ako nije prijavljen, sve poveznice vode na login.php
                    $detaljiUrl = "login.php";
                    $posudiUrl = "login.php";
                    $rezervirajUrl = "login.php";
                }
            ?>
                <div class="col">
                    <div class="card h-100 shadow-sm book-card">
                        <a href="<?php echo $detaljiUrl; ?>">
                            <img src="<?php echo $putanjaSlike; ?>" class="card-img-top" alt="<?php echo $knjiga['naslov']; ?>">
                        </a>
                        <div class="card-body px-2">
                            <h6 class="card-title mb-1 text-dark fw-bold"><?php echo $knjiga['naslov']; ?></h6>
                            <p class="card-text small text-muted"><?php echo $knjiga['autor']; ?></p>
                        </div>
                        <div class="card-footer bg-transparent border-0 pb-3">
                            <div class="d-grid gap-2">
                                <a href="<?php echo $posudiUrl; ?>" class="btn btn-primary btn-sm">Posudi</a>
                                <a href="<?php echo $rezervirajUrl; ?>" class="btn btn-outline-info btn-sm">Rezerviraj</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>