<?php
class PosudbaController {
    private $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    // Dohvaća sve trenutne posudbe
    public function getAllLoans(int $page = 1, int $perPage = 10): array {
        $offset = ($page - 1) * $perPage;
        
        $stmt = $this->conn->prepare("
            SELECT p.PosudbaID, c.Ime, c.Prezime, v.naslov, 
                   p.DatumPosudbe, p.DatumVracanja, pr.IDPrimjerak
            FROM Posudba p
            JOIN Clan c ON p.ClanID = c.IDClan
            JOIN Primjerak pr ON p.PrimjerakID = pr.IDPrimjerak
            JOIN VrstaLiterature v ON pr.LiteraturaID = v.IDLiteratura
            ORDER BY p.DatumPosudbe DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->bind_param("ii", $perPage, $offset);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Dohvaća posudbe određenog člana
    public function getLoansByMember(int $clanID): array {
        $stmt = $this->conn->prepare("
            SELECT p.*, v.naslov, pr.IDPrimjerak
            FROM Posudba p
            JOIN Primjerak pr ON p.PrimjerakID = pr.IDPrimjerak
            JOIN VrstaLiterature v ON pr.LiteraturaID = v.IDLiteratura
            WHERE p.ClanID = ? AND p.DatumVracanja IS NULL
        ");
        $stmt->bind_param("i", $clanID);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Kreiraj novu posudbu
    public function createLoan(int $clanID, int $primjerakID): bool {
        try {
            $this->conn->begin_transaction();

            // Provjeri dostupnost primjerka
            $stmtCheck = $this->conn->prepare("
                SELECT Dostupno FROM Primjerak WHERE IDPrimjerak = ?
            ");
            $stmtCheck->bind_param("i", $primjerakID);
            $stmtCheck->execute();
            $dostupno = $stmtCheck->get_result()->fetch_assoc()['Dostupno'];
            
            if ($dostupno !== 'dostupno') {
                throw new Exception("Primjerak nije dostupan za posudbu");
            }

            // Kreiraj posudbu
            $stmtInsert = $this->conn->prepare("
                INSERT INTO Posudba (ClanID, PrimjerakID, DatumPosudbe)
                VALUES (?, ?, CURDATE())
            ");
            $stmtInsert->bind_param("ii", $clanID, $primjerakID);
            $stmtInsert->execute();

            // Ažuriraj status primjerka
            $stmtUpdate = $this->conn->prepare("
                UPDATE Primjerak 
                SET Dostupno = 'posuđeno' 
                WHERE IDPrimjerak = ?
            ");
            $stmtUpdate->bind_param("i", $primjerakID);
            $stmtUpdate->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Greška pri posudbi: " . $e->getMessage());
            return false;
        }
    }

    // Vrati posuđenu knjigu
    public function returnLoan(int $posudbaID): bool {
        try {
            $this->conn->begin_transaction();

            // Ažuriraj posudbu
            $stmtPosudba = $this->conn->prepare("
                UPDATE Posudba 
                SET DatumVracanja = CURDATE() 
                WHERE PosudbaID = ?
            ");
            $stmtPosudba->bind_param("i", $posudbaID);
            $stmtPosudba->execute();

            // Ažuriraj status primjerka
            $stmtPrimjerak = $this->conn->prepare("
                UPDATE Primjerak p
                JOIN Posudba po ON p.IDPrimjerak = po.PrimjerakID
                SET p.Dostupno = 'dostupno'
                WHERE po.PosudbaID = ?
            ");
            $stmtPrimjerak->bind_param("i", $posudbaID);
            $stmtPrimjerak->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Greška pri vraćanju: " . $e->getMessage());
            return false;
        }
    }

    // Dohvati zakasnine
    public function getOverdueLoans(int $days = 30): array {
        $stmt = $this->conn->prepare("
            SELECT p.*, c.Ime, c.Prezime, v.naslov,
                   DATEDIFF(CURDATE(), p.DatumPosudbe) AS DanaKasni
            FROM Posudba p
            JOIN Clan c ON p.ClanID = c.IDClan
            JOIN Primjerak pr ON p.PrimjerakID = pr.IDPrimjerak
            JOIN VrstaLiterature v ON pr.LiteraturaID = v.IDLiteratura
            WHERE p.DatumVracanja IS NULL
            AND DATEDIFF(CURDATE(), p.DatumPosudbe) > ?
        ");
        $stmt->bind_param("i", $days);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>