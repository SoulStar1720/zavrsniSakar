<?php
class KnjigaController {
    private $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    // Dohvati sve knjige s detaljima autora i izdavača
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

    // Dohvati knjigu po ID-u
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

    // Dodaj novu knjigu
    public function addBook(array $bookData): bool {
        try {
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

    // Obriši knjigu
    public function deleteBook(int $id): bool {
        try {
            $this->conn->begin_transaction();
            
            // Prvo obriši primjerke
            $stmt1 = $this->conn->prepare("DELETE FROM Primjerak WHERE LiteraturaID = ?");
            $stmt1->bind_param("i", $id);
            $stmt1->execute();
            
            // Zatim obriši knjigu
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
}
?>