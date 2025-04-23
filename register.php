<?php
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

    // Validacija
    if (empty($ime) || empty($prezime) || empty($email) || empty($lozinka)) {
        $error = "Sva polja su obavezna";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Neispravan format emaila";
    } else {
        // Provjera emaila
        $stmt = $conn->prepare("SELECT IDClan FROM Clan WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows > 0) {
            $error = "Email već postoji u sustavu";
        } else {
            // Unos u bazu
            $hashedPassword = password_hash($lozinka, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO Clan (Ime, Prezime, Email, Lozinka) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $ime, $prezime, $email, $hashedPassword);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = "Registracija uspješna! Prijavite se";
                header("Location: login.php");
                exit();
            } else {
                $error = "Greška pri registraciji";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registracija</title>
    <style>
        :root {
            --bg-color: #ebecec;
            --shadow-dark: #a3b1c6;
            --shadow-light: #ffffff;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: var(--bg-color);
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .neumorphic-card {
            background: var(--bg-color);
            border-radius: 20px;
            padding: 40px;
            width: 300px;
            box-shadow: 18px 18px 30px var(--shadow-dark),
                       -18px -18px 30px var(--shadow-light);
        }

        .neumorphic-input {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: none;
            background: var(--bg-color);
            border-radius: 15px;
            box-shadow: inset 8px 8px 15px var(--shadow-dark),
                       inset -8px -8px 15px var(--shadow-light);
        }

        .neumorphic-button {
            width: 100%;
            padding: 15px;
            margin-top: 20px;
            border: none;
            background: var(--bg-color);
            border-radius: 15px;
            box-shadow: 8px 8px 15px var(--shadow-dark),
                       -8px -8px 15px var(--shadow-light);
            cursor: pointer;
        }

        .error-message {
            color: #ff4444;
            margin: 15px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="neumorphic-card">
        <h1 style="text-align: center; margin-bottom: 25px;">Registracija</h1>
        
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

        <div style="margin-top: 25px; text-align: center;">
            <a href="login.php" style="color: #666; text-decoration: none;">Već imate račun? Prijava</a>
        </div>
    </div>
</body>
</html>