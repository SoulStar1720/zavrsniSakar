<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/controllers/KnjigaController.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$knjigaController = new KnjigaController($conn);
$knjiga = $knjigaController->getBookById($id);

if (!$knjiga) {
    die("Knjiga nije pronađena.");
}

$imeSlike = basename($knjiga['naslovnica']);
$putanjaSlike = "naslovnice/" . $imeSlike;
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <?php include __DIR__ . '/includes/header.php'; ?>
</head>
<body>
    <div class="container py-5">
        <a href="index.php" class="btn btn-link mb-4 p-0 text-decoration-none">← Povratak na popis</a>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <img src="<?php echo $putanjaSlike; ?>" class="img-fluid rounded shadow" alt="<?php echo $knjiga['naslov']; ?>">
            </div>
            <div class="col-md-8">
                <h1 class="display-5 fw-bold"><?php echo $knjiga['naslov']; ?></h1>
                <p class="fs-4 text-muted"><?php echo $knjiga['autor']; ?></p>
                <hr>
                
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Izdavač:</th>
                        <td><?php echo $knjiga['izdavac']; ?> (<?php echo $knjiga['godina_izdanja']; ?>.)</td>
                    </tr>
                    <tr>
                        <th>ISBN:</th>
                        <td><?php echo $knjiga['ISBN_broj']; ?></td>
                    </tr>
                    <tr>
                        <th>Kategorija:</th>
                        <td><span class="badge bg-secondary"><?php echo $knjiga['vrsta']; ?></span></td>
                    </tr>
                    <tr>
                        <th>Dostupno:</th>
                        <td><?php echo $knjiga['broj_primjeraka']; ?> kom</td>
                    </tr>
                </table>

                <div class="mt-4 p-4 bg-light rounded">
                    <h5>Opis knjige</h5>
                    <p><?php echo nl2br($knjiga['opis']); ?></p>
                </div>

                <div class="mt-4 gap-2 d-flex">
                    <a href="views/posudbe/posudi.php?knjiga_id=<?php echo $id; ?>" class="btn btn-primary btn-lg px-5">Posudi knjigu</a>
                    <a href="rezerviraj.php?id=<?php echo $id; ?>" class="btn btn-outline-info btn-lg px-5">Rezerviraj</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>