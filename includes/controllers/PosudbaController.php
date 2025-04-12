<?php
class PosudbaController {
    private $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    // Dohvaćanje svih posudbi s detaljima
    public function getAllLoans(int $page = 1, int $perPage = 10): array {
        $offset = ($page - 1) * $perPage;
        
        $stmt = $this->conn->prepare("
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
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Kreiranje posudbe s transakcijom i provjerama
    public function createLoan(int $clanID, int $primjerakID): bool {
        try {
            $this->conn->begin_transaction();

            // Provjeri valjanost ID-jeva
            $this->validateIds($clanID, $primjerakID);

            // Provjeri dostupnost primjerka
            $dostupno = $this->checkAvailability($primjerakID);
            if ($dostupno !== 'dostupno') {
                throw new Exception("Primjerak nije dostupan");
            }

            // Kreiraj posudbu
            $stmtInsert = $this->conn->prepare("
                INSERT INTO Posudba (ClanID, PrimjerakID, DatumPosudbe)
                VALUES (?, ?, CURDATE())
            ");
            $stmtInsert->bind_param("ii", $clanID, $primjerakID);
            $stmtInsert->execute();

            // Ažuriraj status primjerka
            $this->updatePrimjerakStatus($primjerakID, 'posuđeno');

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Greška pri posudbi: " . $e->getMessage());
            return false;
        }
    }

    // Vraćanje posudbe s transakcijom
    public function returnLoan(int $posudbaID): bool {
        try {
            $this->conn->begin_transaction();

            // Dohvati primjerakID prije brisanja
            $primjerakID = $this->getPrimjerakId($posudbaID);

            // Ažuriraj datum vraćanja
            $stmt = $this->conn->prepare("
                UPDATE Posudba 
                SET DatumVracanja = CURDATE() 
                WHERE PosudbaID = ?
            ");
            $stmt->bind_param("i", $posudbaID);
            $stmt->execute();

            // Ažuriraj status primjerka
            $this->updatePrimjerakStatus($primjerakID, 'dostupno');

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Greška pri vraćanju: " . $e->getMessage());
            return false;
        }
    }

    // Privatne metode za validaciju
    private function validateIds(int $clanID, int $primjerakID): void {
        // Provjera postoji li član
        $stmtClan = $this->conn->prepare("SELECT IDClan FROM Clan WHERE IDClan = ?");
        $stmtClan->bind_param("i", $clanID);
        $stmtClan->execute();
        if ($stmtClan->get_result()->num_rows === 0) {
            throw new Exception("Nevažeći ID člana");
        }

        // Provjera postoji li primjerak
        $stmtPrim = $this->conn->prepare("SELECT IDPrimjerak FROM Primjerak WHERE IDPrimjerak = ?");
        $stmtPrim->bind_param("i", $primjerakID);
        $stmtPrim->execute();
        if ($stmtPrim->get_result()->num_rows === 0) {
            throw new Exception("Nevažeći ID primjerka");
        }
    }

    private function checkAvailability(int $primjerakID): string {
        $stmt = $this->conn->prepare("
            SELECT Dostupno FROM Primjerak WHERE IDPrimjerak = ?
        ");
        $stmt->bind_param("i", $primjerakID);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['Dostupno'] ?? 'nepoznato';
    }

    private function updatePrimjerakStatus(int $primjerakID, string $status): void {
        $stmt = $this->conn->prepare("
            UPDATE Primjerak 
            SET Dostupno = ?, ClanID = NULL, DatumPosudbe = NULL
            WHERE IDPrimjerak = ?
        ");
        $statusValue = ($status === 'posuđeno') ? 'posuđeno' : 'dostupno';
        $stmt->bind_param("si", $statusValue, $primjerakID);
        $stmt->execute();
    }

    private function getPrimjerakId(int $posudbaID): int {
        $stmt = $this->conn->prepare("
            SELECT PrimjerakID FROM Posudba WHERE PosudbaID = ?
        ");
        $stmt->bind_param("i", $posudbaID);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['PrimjerakID'] ?? 0;
    }
}
?>