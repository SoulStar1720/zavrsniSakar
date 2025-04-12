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
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM Clan WHERE Email = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['Lozinka'])) {
        $_SESSION['user_id'] = $user['IDClan'];
        $_SESSION['user_role'] = $user['role'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Pogrešno korisničko ime ili lozinka";
    }
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prijava</title>
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
            transition: 0.3s;
        }

        .neumorphic-button:hover {
            box-shadow: 4px 4px 8px var(--shadow-dark),
                       -4px -4px 8px var(--shadow-light);
        }

        .error-message {
            color: #ff4444;
            margin-top: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="neumorphic-card">
        <h1>Prijava</h1>
        <?php if (!empty($error)): ?>
            <div class="error-message"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" class="neumorphic-input" placeholder="Email" required>
            <input type="password" name="password" class="neumorphic-input" placeholder="Lozinka" required>
            <button type="submit" class="neumorphic-button">Prijavi se</button>
        </form>

        <div style="margin-top: 20px; text-align: center;">
            <a href="register.php" style="color: #666; text-decoration: none;">Registriraj se</a>
        </div>
    </div>
</body>
</html>