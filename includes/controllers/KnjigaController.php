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

// Get all books with pagination
$stmt = $conn->prepare("
    SELECT v.IDLiteratura, v.naslov, v.ISBN_broj, v.broj_primjeraka,
           a.ImePrezime AS autor, i.Naziv AS izdavac
    FROM VrstaLiterature v
    JOIN Autor a ON v.AutorID = a.AutorID
    JOIN Izdavac i ON v.IzdavacID = i.IzdavacID
    ORDER BY v.naslov
    LIMIT ? OFFSET ?
");
$stmt->bind_param("ii", $perPage, $offset);
$stmt->execute();
$knjige = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get book by ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $conn->prepare("
    SELECT v.*, a.ImePrezime, i.Naziv 
    FROM VrstaLiterature v
    JOIN Autor a ON v.AutorID = a.AutorID
    JOIN Izdavac i ON v.IzdavacID = i.IzdavacID
    WHERE v.IDLiteratura = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$knjiga = $stmt->get_result()->fetch_assoc();

// Add a new book
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $bookData = [
        'naslov' => trim($_POST['naslov']),
        'autor_id' => (int)$_POST['autor_id'],
        'izdavac_id' => (int)$_POST['izdavac_id'],
        'isbn' => trim($_POST['isbn']),
        'broj_primjeraka' => (int)$_POST['broj_primjeraka'],
        'vrsta' => trim($_POST['vrsta'])
    ];

    try {
        // Validate IDs
        if (empty($bookData['autor_id']) || empty($bookData['izdavac_id'])) {
            throw new InvalidArgumentException("Autor ID and Izdavac ID must be provided.");
        }

        // Validate author ID
        $stmtAutor = $conn->prepare("SELECT AutorID FROM Autor WHERE AutorID = ?");
        $stmtAutor->bind_param("i", $bookData['autor_id']);
        $stmtAutor->execute();
        if ($stmtAutor->get_result()->num_rows === 0) {
            throw new Exception("Nevažeći ID autora");
        }

        // Validate publisher ID
        $stmtIzdavac = $conn->prepare("SELECT IzdavacID FROM Izdavac WHERE IzdavacID = ?");
        $stmtIzdavac->bind_param("i", $bookData['izdavac_id']);
        $stmtIzdavac->execute();
        if ($stmtIzdavac->get_result()->num_rows === 0) {
            throw new Exception("Nevažeći ID izdavača");
        }

        // Prepare insert statement
        $stmt = $conn->prepare("
            INSERT INTO VrstaLiterature (naslov, AutorID, IzdavacID, ISBN_broj, broj_primjeraka, vrsta)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("siissi", $bookData['naslov'], $bookData['autor_id'], $bookData['izdavac_id'], $bookData['isbn'], $bookData['broj_primjeraka'], $bookData['vrsta']);
        
        $stmt->execute();
        echo "Knjiga uspješno dodana!";
    } catch (mysqli_sql_exception $e) {
        error_log("Greška pri dodavanju knjige: " . $e->getMessage());
    } catch (Exception $e) {
        error_log("Greška: " . $e->getMessage());
    }
}

// Update book
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $bookData = [
        'naslov' => trim($_POST['naslov']),
        'autor_id' => (int)$_POST['autor_id'],
        'izdavac_id' => (int)$_POST['izdavac_id'],
        'isbn' => trim($_POST['isbn']),
        'broj_primjeraka' => (int)$_POST['broj_primjeraka'],
        'vrsta' => trim($_POST['vrsta']),
        'id' => $id
    ];

    try {
        // Validate IDs
        if (empty($bookData['autor_id']) || empty($bookData['izdavac_id'])) {
            throw new InvalidArgumentException("Autor ID and Izdavac ID must be provided.");
        }

        // Validate author ID
        $stmtAutor = $conn->prepare("SELECT AutorID FROM Autor WHERE AutorID = ?");
        $stmtAutor->bind_param("i", $bookData['autor_id']);
        $stmtAutor->execute();
        if ($stmtAutor->get_result()->num_rows === 0) {
            throw new Exception("Nevažeći ID autora");
        }

        // Validate publisher ID
        $stmtIzdavac = $conn->prepare("SELECT IzdavacID FROM Izdavac WHERE IzdavacID = ?");
        $stmtIzdavac->bind_param("i", $bookData['izdavac_id']);
        $stmtIzdavac->execute();
        if ($stmtIzdavac->get_result()->num_rows === 0) {
            throw new Exception("Nevažeći ID izdavača");
        }

        // Prepare update statement
        $stmt = $conn->prepare("
            UPDATE VrstaLiterature 
            SET naslov = ?, AutorID = ?, IzdavacID = ?, ISBN_broj = ?, broj_primjeraka = ?, vrsta = ?
            WHERE IDLiteratura = ?
        ");
        $stmt->bind_param("siisssi", $bookData['naslov'], $bookData['autor_id'], $bookData['izdavac_id'], $bookData['isbn'], $bookData['broj_primjeraka'], $bookData['vrsta'], $bookData['id']);
        
        $stmt->execute();
        echo "Knjiga uspješno ažurirana!";
    } catch (mysqli_sql_exception $e) {
        error_log("Greška pri ažuriranju knjige: " . $e->getMessage());
    }
}

// Delete book
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    try {
        // Check for active loans
        $stmtCheck = $conn->prepare("
            SELECT COUNT(*) 
            FROM Primjerak p
            JOIN Posudba po ON p.IDPrimjerak = po.PrimjerakID
            WHERE p.LiteraturaID = ? AND po.DatumVracanja IS NULL
        ");
        $stmtCheck->bind_param("i", $id);
        $stmtCheck->execute();
        
        if ($stmtCheck->get_result()->fetch_row()[0] > 0) {
            throw new Exception("Ne možete obrisati knjigu s aktivnim posudbama");
        }

        // Delete book
        $stmt = $conn->prepare("DELETE FROM VrstaLiterature WHERE IDLiteratura = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo "Knjiga uspješno obrisana!";
    } catch (mysqli_sql_exception $e) {
        error_log("Greška pri brisanju knjige: " . $e->getMessage());
    }
}

// Close the connection
$conn->close();
?>
