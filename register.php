<?php
session_start();
require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/auth.php';

if (isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ime = trim($_POST['ime']);
    $prezime = trim($_POST['prezime']);
    $email = trim($_POST['email']);
    $lozinka = trim($_POST['lozinka']);

    // Provjera postoji li email
    $stmt = $conn->prepare("SELECT IDClan FROM Clan WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows > 0) {
        $error = "Email već postoji u sustavu";
    } else {
        $hashedPassword = password_hash($lozinka, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO Clan (Ime, Prezime, Email, Lozinka) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $ime, $prezime, $email, $hashedPassword);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Registracija uspješna! Možete se prijaviti";
            header("Location: login.php");
            exit();
        } else {
            $error = "Greška pri registraciji";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    </head>
<body>
    <div class="neumorphic-card">
        <h1>Registracija</h1>
        <?php if (!empty($error)): ?>
            <div class="error-message"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="ime" class="neumorphic-input" placeholder="Ime" required>
            <input type="text" name="prezime" class="neumorphic-input" placeholder="Prezime" required>
            <input type="email" name="email" class="neumorphic-input" placeholder="Email" required>
            <input type="password" name="lozinka" class="neumorphic-input" placeholder="Lozinka" required>
            <button type="submit" class="neumorphic-button">Registriraj se</button>
        </form>

        <div style="margin-top: 20px; text-align: center;">
            <a href="login.php" style="color: #666; text-decoration: none;">Već imate račun? Prijava</a>
        </div>
    </div>
</body>
</html>