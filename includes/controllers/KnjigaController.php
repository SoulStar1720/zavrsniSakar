<?php
class KnjigaController {
    private $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    // Dohvati sve knjige s autorom i izdavačem 
    public function getAllBooks(int $page = 1, int $perPage = 10): array {

    $offset = ($page - 1) * $perPage;

    $stmt = $this->conn->prepare("
        SELECT 
        k.IDKnjiga AS IDVrsta,
        k.naslov,
        k.ISBN_broj,
        k.broj_primjeraka,
        k.naslovnica,
        a.ImePrezime AS autor,
        i.Naziv AS izdavac
        FROM knjige k
        JOIN autor a ON k.AutorID = a.AutorID
        JOIN izdavac i ON k.IzdavacID = i.IzdavacID
        ORDER BY k.naslov
        LIMIT ? OFFSET ?
    ");

    $stmt->bind_param("ii", $perPage, $offset);
    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

    // Dohvati knjigu po ID-u s provjerom referenci
    public function getBookById(int $id): ?array {
        $stmt = $this->conn->prepare("
    SELECT 
        k.*, 
        a.ImePrezime AS autor, 
        i.Naziv AS izdavac,
        v.naziv AS vrsta
    FROM knjige k
    JOIN autor a ON k.AutorID = a.AutorID
    JOIN izdavac i ON k.IzdavacID = i.IzdavacID
    JOIN vrsta v ON k.VrstaID = v.IDVrsta
    WHERE k.IDKnjiga = ?
");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $result = $stmt->get_result()->fetch_assoc();
        return $result ?: null;
    }

    // Pronađi autora ili ga dodaj ako ne postoji
    private function getOrCreateAutor(string $imePrezime): int {
        $stmt = $this->conn->prepare("SELECT AutorID FROM autor WHERE ImePrezime = ?");
        $stmt->bind_param("s", $imePrezime);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if ($row) return (int)$row['AutorID'];

        $stmt2 = $this->conn->prepare("INSERT INTO autor (ImePrezime) VALUES (?)");
        $stmt2->bind_param("s", $imePrezime);
        $stmt2->execute();
        return (int)$this->conn->insert_id;
    }

    // Pronađi izdavača ili ga dodaj ako ne postoji
    private function getOrCreateIzdavac(string $naziv): int {
        $stmt = $this->conn->prepare("SELECT IzdavacID FROM izdavac WHERE Naziv = ?");
        $stmt->bind_param("s", $naziv);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if ($row) return (int)$row['IzdavacID'];

        $stmt2 = $this->conn->prepare("INSERT INTO izdavac (Naziv) VALUES (?)");
        $stmt2->bind_param("s", $naziv);
        $stmt2->execute();
        return (int)$this->conn->insert_id;
    }

    // Pronađi vrstu po nazivu
    private function getVrstaId(string $naziv): int {
        $stmt = $this->conn->prepare("SELECT IDVrsta FROM vrsta WHERE naziv = ?");
        $stmt->bind_param("s", $naziv);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if (!$row) throw new Exception("Vrsta literature nije pronađena");
        return (int)$row['IDVrsta'];
    }

    // Dodaj novu knjigu
    public function addBook(array $bookData): bool {
        try {
            $autorId   = $this->getOrCreateAutor(trim($bookData['autor']));
            $izdavacId = $this->getOrCreateIzdavac(trim($bookData['izdavac']));
            $vrstaId   = $this->getVrstaId(trim($bookData['vrsta']));

            $naslov         = trim($bookData['naslov']);
            $isbn           = trim($bookData['isbn'] ?? '');
            $brojPrimjeraka = (int)($bookData['broj_primjeraka'] ?? 1);
            $naslovnica     = $bookData['naslovnica'] ?? null;

            $stmt = $this->conn->prepare("
                INSERT INTO knjige (naslov, AutorID, IzdavacID, VrstaID, ISBN_broj, broj_primjeraka, naslovnica)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("siiisis", $naslov, $autorId, $izdavacId, $vrstaId, $isbn, $brojPrimjeraka, $naslovnica);
            return $stmt->execute();

        } catch (Exception $e) {
            error_log("Greška pri dodavanju knjige: " . $e->getMessage());
            throw $e;
        }
    }

    // Obriši knjigu s transakcijom i provjerom posudbi
    public function deleteBook(int $id): bool {
        try {
            $this->conn->begin_transaction();
            
            // Provjeri postoje li aktivne posudbe
            $stmtCheck = $this->conn->prepare("
                SELECT COUNT(*) 
                FROM Primjerak p
                JOIN Posudba po ON p.IDPrimjerak = po.PrimjerakID
                WHERE p.KnjigaID = ? AND po.DatumVracanja IS NULL
            ");
            $stmtCheck->bind_param("i", $id);
            $stmtCheck->execute();
            
            if ($stmtCheck->get_result()->fetch_row()[0] > 0) {
                throw new Exception("Ne možete obrisati knjigu s aktivnim posudbama");
            }

            // Obriši primjerke
            $stmt1 = $this->conn->prepare("DELETE FROM primjerak WHERE KnjigaID = ?");
            $stmt1->bind_param("i", $id);
            $stmt1->execute();
            
            // Obriši knjigu
            $stmt2 = $this->conn->prepare("DELETE FROM knjige WHERE IDKnjiga = ?");
            $stmt2->bind_param("i", $id);
            $stmt2->execute();
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Greška pri brisanju: " . $e->getMessage());
            return false;
        }
    }

    // Pretraži knjige po naslovu i autoru
    public function searchBooks(string $query): array {
        $searchTerm = "%$query%";
        
        $stmt = $this->conn->prepare("
            SELECT v.IDVrsta, v.naslov, a.ImePrezime AS autor
            FROM Vrsta v
            JOIN Autor a ON v.AutorID = a.AutorID
            WHERE v.naslov LIKE ? 
               OR a.ImePrezime LIKE ?
        ");
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function countBooks(): int {
    $result = $this->conn->query("SELECT COUNT(*) AS total FROM knjige");
    $row = $result->fetch_assoc();
    return (int)$row['total'];
}
public function updateBook(int $id, array $bookData): bool {
    try {
        $autorId   = $this->getOrCreateAutor(trim($bookData['autor']));
        $izdavacId = $this->getOrCreateIzdavac(trim($bookData['izdavac']));
        $vrstaId   = $this->getVrstaId(trim($bookData['vrsta']));

        $naslov         = trim($bookData['naslov']);
        $isbn           = trim($bookData['isbn'] ?? '');
        $brojPrimjeraka = (int)($bookData['broj_primjeraka'] ?? 1);
        $naslovnica     = $bookData['naslovnica'] ?? null;

        $stmt = $this->conn->prepare("
            UPDATE knjige SET
                naslov = ?,
                AutorID = ?,
                IzdavacID = ?,
                VrstaID = ?,
                ISBN_broj = ?,
                broj_primjeraka = ?,
                naslovnica = ?
            WHERE IDKnjiga = ?
        ");
        $stmt->bind_param("siiisisi", $naslov, $autorId, $izdavacId, $vrstaId, $isbn, $brojPrimjeraka, $naslovnica, $id);
        return $stmt->execute();
    } catch (Exception $e) {
        error_log("Greška pri ažuriranju knjige: " . $e->getMessage());
        throw $e;
    }
}
}
?>