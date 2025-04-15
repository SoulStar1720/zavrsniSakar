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
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Dohvati knjigu po ID-u s provjerom referenci
    public function getBookById(int $id): ?array {
        $stmt = $this->conn->prepare("
            SELECT v.*, a.ImePrezime, i.Naziv 
            FROM VrstaLiterature v
            JOIN Autor a ON v.AutorID = a.AutorID
            JOIN Izdavac i ON v.IzdavacID = i.IzdavacID
            WHERE v.IDLiteratura = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $result = $stmt->get_result()->fetch_assoc();
        return $result ?: null;
    }

    // Dodaj novu knjigu s provjerom validnosti ID-jeva
    public function addBook(array $bookData): bool {
        try {
            // Validacija ID-jeva
            $this->validateIds($bookData['autor_id'], $bookData['izdavac_id']);
            
            $stmt = $this->conn->prepare("
                INSERT INTO VrstaLiterature 
                (vrsta_literature, AutorID, IzdavacID, naslov, ISBN_broj, broj_primjeraka) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->bind_param(
                "siissi",
                $bookData['vrsta'],
                $bookData['autor_id'],
                $bookData['izdavac_id'],
                $bookData['naslov'],
                $bookData['isbn'],
                $bookData['broj_primjeraka']
            );
            
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            error_log("Greška pri dodavanju knjige: " . $e->getMessage());
            return false;
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
                WHERE p.LiteraturaID = ? AND po.DatumVracanja IS NULL
            ");
            $stmtCheck->bind_param("i", $id);
            $stmtCheck->execute();
            
            if ($stmtCheck->get_result()->fetch_row()[0] > 0) {
                throw new Exception("Ne možete obrisati knjigu s aktivnim posudbama");
            }

            // Obriši primjerke
            $stmt1 = $this->conn->prepare("DELETE FROM Primjerak WHERE LiteraturaID = ?");
            $stmt1->bind_param("i", $id);
            $stmt1->execute();
            
            // Obriši knjigu
            $stmt2 = $this->conn->prepare("DELETE FROM VrstaLiterature WHERE IDLiteratura = ?");
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

    // Privatna metoda za validaciju ID-jeva
    private function validateIds(int $autorId, int $izdavacId): void {
        $stmtAutor = $this->conn->prepare("SELECT AutorID FROM Autor WHERE AutorID = ?");
        $stmtAutor->bind_param("i", $autorId);
        $stmtAutor->execute();
        if ($stmtAutor->get_result()->num_rows === 0) {
            throw new Exception("Nevažeći ID autora");
        }

        $stmtIzdavac = $this->conn->prepare("SELECT IzdavacID FROM Izdavac WHERE IzdavacID = ?");
        $stmtIzdavac->bind_param("i", $izdavacId);
        $stmtIzdavac->execute();
        if ($stmtIzdavac->get_result()->num_rows === 0) {
            throw new Exception("Nevažeći ID izdavača");
        }
    }

    // Pretraži knjige po naslovu i autoru
    public function searchBooks(string $query): array {
        $searchTerm = "%$query%";
        
        $stmt = $this->conn->prepare("
            SELECT v.IDLiteratura, v.naslov, a.ImePrezime AS autor
            FROM VrstaLiterature v
            JOIN Autor a ON v.AutorID = a.AutorID
            WHERE v.naslov LIKE ? 
               OR a.ImePrezime LIKE ?
        ");
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    public function countBooks(): int {
        $result = $this->conn->query("SELECT COUNT(*) AS total FROM VrstaLiterature");
        $row = $result->fetch_assoc();
        return (int) $row['total'];
    }
}
?>