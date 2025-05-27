<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db_connection.php';
require_once __DIR__ . '/../../includes/header.php';

requireAdmin();

$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $memberData = [
        'ime' => trim($_POST['ime']),
        'prezime' => trim($_POST['prezime']),
        'email' => trim($_POST['email']),
        'lozinka' => trim($_POST['lozinka']),
        'role' => $_POST['role'] ?? 'user'
    ];

    try {
        // Validate email
        if (empty($memberData['email']) || !filter_var($memberData['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Neispravan email format");
        }

        // Check if email already exists
        $checkStmt = $conn->prepare("SELECT IDClan FROM Clan WHERE Email = ?");
        $checkStmt->bind_param("s", $memberData['email']);
        $checkStmt->execute();
        if ($checkStmt->get_result()->num_rows > 0) {
            throw new Exception("Email već postoji u sustavu");
        }

        // Prepare insert statement
        $stmt = $conn->prepare("
            INSERT INTO Clan 
            (Ime, Prezime, Email, Lozinka, role) 
            VALUES (?, ?, ?, ?, ?)
        ");

        // Hash the password
        $hashedPassword = password_hash($memberData['lozinka'], PASSWORD_BCRYPT);

        // Ensure all required fields are set
        $ime = $memberData['ime'] ?? null;
        $prezime = $memberData['prezime'] ?? null;
        $email = $memberData['email'] ?? null;
        $role = $memberData['role'] ?? 'user'; // Default to 'user'

        // Check for null values
        if (is_null($ime) || is_null($prezime) || is_null($email) || is_null($hashedPassword)) {
            throw new Exception("Sva polja su obavezna");
        }

        // Bind parameters
        $stmt->bind_param("sssss", $ime, $prezime, $email, $hashedPassword, $role);
        
        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['success'] = "Član uspješno dodan!";
            header("Location: index.php");
            exit();
        } else {
            throw new Exception("Greška pri dodavanju člana");
        }
    } catch (mysqli_sql_exception $e) {
        error_log("Greška pri dodavanju člana: " . $e->getMessage());
        $error = "Greška pri dodavanju člana: " . $e->getMessage();
    } catch (Exception $e) {
        error_log("Greška: " . $e->getMessage());
        $error = $e->getMessage();
    }
}
?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">
                <i class="bi bi-person-plus"></i> Dodaj novog člana
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
                    <div class="col-md-6">
                        <label class="form-label">Ime</label>
                        <input type="text" name="ime" class="form-control" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Prezime</label>
                        <input type="text" name="prezime" class="form-control" required>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label">Lozinka</label>
                        <input type="password" name="lozinka" class="form-control" required>
                    </div>
                    
                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                    <div class="col-md-6">
                        <label class="form-label">Uloga</label>
                        <select name="role" class="form-select">
                            <option value="user">Obični korisnik</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </div>
                    <?php endif; ?>
                    
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Spremi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Close the database connection
$conn->close();
?>
