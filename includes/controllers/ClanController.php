<?php
// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'knjiznica');

if (!$conn) {
    die("Greška pri spajanju na bazu: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");

// Pagination settings
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

// Get all members with pagination
$stmt = $conn->prepare("
    SELECT IDClan, Ime, Prezime, Email, role 
    FROM Clan 
    ORDER BY Prezime, Ime 
    LIMIT ? OFFSET ?
");
$stmt->bind_param("ii", $perPage, $offset);
$stmt->execute();
$clanovi = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get member by ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $conn->prepare("
    SELECT IDClan, Ime, Prezime, Email, role 
    FROM Clan 
    WHERE IDClan = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$clan = $stmt->get_result()->fetch_assoc();

// Add a new member
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
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
        
        $stmt->execute();
        echo "Član uspješno dodan!";
    } catch (mysqli_sql_exception $e) {
        error_log("Greška pri dodavanju člana: " . $e->getMessage());
    } catch (Exception $e) {
        error_log("Greška: " . $e->getMessage());
    }
}

// Update member
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $memberData = [
        'ime' => trim($_POST['ime']),
        'prezime' => trim($_POST['prezime']),
        'email' => trim($_POST['email']),
        'lozinka' => trim($_POST['lozinka']),
        'id' => $id
    ];

    try {
        $sql = "UPDATE Clan SET 
                Ime = ?, 
                Prezime = ?, 
                Email = ? 
                " . (isset($memberData['lozinka']) && !empty($memberData['lozinka']) ? ", Lozinka = ?" : "") . "
                WHERE IDClan = ?";

        $stmt = $conn->prepare($sql);
        
        $params = [
            $memberData['ime'],
            $memberData['prezime'],
            $memberData['email']
        ];
        
        if (isset($memberData['lozinka']) && !empty($memberData['lozinka'])) {
            $hashedPassword = password_hash($memberData['lozinka'], PASSWORD_BCRYPT);
            $params[] = $hashedPassword;
        }
        
        $params[] = $id;
        
        $stmt->bind_param(str_repeat("s", count($params)), ...$params);
        $stmt->execute();
        echo "Član uspješno ažuriran!";
    } catch (mysqli_sql_exception $e) {
        error_log("Greška pri ažuriranju člana: " . $e->getMessage());
    }
}

// Delete member
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    try {
        $posudbeStmt = $conn->prepare("
            SELECT COUNT(*) FROM Posudba 
            WHERE ClanID = ? AND DatumVracanja IS NULL
        ");
        $posudbeStmt->bind_param("i", $id);
        $posudbeStmt->execute();
        
        if ($posudbeStmt->get_result()->fetch_row()[0] > 0) {
            throw new Exception("Član ima aktivne posudbe");
        }

        $stmt = $conn->prepare("
            DELETE FROM Clan 
            WHERE IDClan = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo "Član uspješno obrisan!";
    } catch (mysqli_sql_exception $e) {
        error_log("Greška pri brisanju člana: " . $e->getMessage());
    }
}

// Close the connection
$conn->close();
?>
