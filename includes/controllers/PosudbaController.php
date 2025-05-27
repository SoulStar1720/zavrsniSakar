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

// Get all loans with details
$stmt = $conn->prepare("
    SELECT p.PosudbaID, c.Ime, c.Prezime, 
           v.naslov, p.DatumPosudbe, p.DatumVracanja,
           pr.IDPrimjerak, a.ImePrezime AS autor
    FROM Posudba p
    JOIN Clan c ON p.ClanID = c.IDClan
    JOIN Primjerak pr ON p.PrimjerakID = pr.IDPrimjerak
    JOIN VrstaLiterature v ON pr.LiteraturaID = v.IDLiteratura
    JOIN Autor a ON v.AutorID = a.AutorID
    ORDER BY p.DatumPosudbe DESC
    LIMIT ? OFFSET ?
");
$stmt->bind_param("ii", $perPage, $offset);
$stmt->execute();
$posudbe = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Create a new loan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $clanID = (int)$_POST['clanID'];
    $primjerakID = (int)$_POST['primjerakID'];

    try {
        // Validate IDs
        if (empty($clanID) || empty($primjerakID)) {
            throw new InvalidArgumentException("Član ID i Primjerak ID moraju biti navedeni.");
        }

        // Validate member ID
        $stmtClan = $conn->prepare("SELECT IDClan FROM Clan WHERE IDClan = ?");
        $stmtClan->bind_param("i", $clanID);
        $stmtClan->execute();
        if ($stmtClan->get_result()->num_rows === 0) {
            throw new Exception("Nevažeći ID člana");
        }

        // Validate copy availability
        $stmtAvailability = $conn->prepare("
            SELECT Dostupno FROM Primjerak WHERE IDPrimjerak = ?
        ");
        $stmtAvailability->bind_param("i", $primjerakID);
        $stmtAvailability->execute();
        $availability = $stmtAvailability->get_result()->fetch_assoc()['Dostupno'] ?? 'nepoznato';
        if ($availability !== 'dostupno') {
            throw new Exception("Primjerak nije dostupan");
        }

        // Create loan
        $stmtInsert = $conn->prepare("
            INSERT INTO Posudba (ClanID, PrimjerakID, DatumPosudbe)
            VALUES (?, ?, CURDATE())
        ");
        $stmtInsert->bind_param("ii", $clanID, $primjerakID);
        $stmtInsert->execute();

        // Update copy status
        $stmtUpdate = $conn->prepare("
            UPDATE Primjerak 
            SET Dostupno = 'posuđeno', ClanID = ?, DatumPosudbe = CURDATE()
            WHERE IDPrimjerak = ?
        ");
        $stmtUpdate->bind_param("ii", $clanID, $primjerakID);
        $stmtUpdate->execute();

        echo "Knjiga uspješno posuđena!";
    } catch (mysqli_sql_exception $e) {
        error_log("Greška pri posudbi: " . $e->getMessage());
    } catch (Exception $e) {
        error_log("Greška: " . $e->getMessage());
    }
}

// Return a loan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'return') {
    $posudbaID = (int)$_POST['posudbaID'];

    try {
        // Get copy ID before returning
        $stmtPrimjerakID = $conn->prepare("
            SELECT PrimjerakID FROM Posudba WHERE PosudbaID = ?
        ");
        $stmtPrimjerakID->bind_param("i", $posudbaID);
        $stmtPrimjerakID->execute();
        $primjerakID = $stmtPrimjerakID->get_result()->fetch_assoc()['PrimjerakID'] ?? 0;

        // Update return date
        $stmtReturn = $conn->prepare("
            UPDATE Posudba 
            SET DatumVracanja = CURDATE() 
            WHERE PosudbaID = ?
        ");
        $stmtReturn->bind_param("i", $posudbaID);
        $stmtReturn->execute();

        // Update copy status
        $stmtUpdate = $conn->prepare("
            UPDATE Primjerak 
            SET Dostupno = 'dostupno', ClanID = NULL, DatumPosudbe = NULL
            WHERE IDPrimjerak = ?
        ");
        $stmtUpdate->bind_param("i", $primjerakID);
        $stmtUpdate->execute();

        echo "Knjiga je uspješno vraćena!";
    } catch (mysqli_sql_exception $e) {
        error_log("Greška pri vraćanju: " . $e->getMessage());
    }
}

// Close the connection
$conn->close();
?>
