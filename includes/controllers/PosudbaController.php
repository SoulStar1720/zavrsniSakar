<?php
class PosudbaController {
    private $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    // Dohvaćanje svih posudbi (ISPRAVLJENO)
    public function getAllLoans(int $page = 1, int $perPage = 10): array {

        $offset = ($page - 1) * $perPage;

        $stmt = $this->conn->prepare("
            SELECT 
                p.IDPosudba,
                c.Ime,
                c.Prezime,
                k.naslov,
                p.DatumPosudbe,
                p.DatumVracanja,
                pr.IDPrimjerak,
                a.ImePrezime AS autor
            FROM posudba p
            JOIN clan c ON p.ClanID = c.IDClan
            JOIN primjerak pr ON p.PrimjerakID = pr.IDPrimjerak
            JOIN knjige k ON pr.KnjigaID = k.IDKnjiga
            JOIN autor a ON k.AutorID = a.AutorID
            ORDER BY p.DatumPosudbe DESC
            LIMIT ? OFFSET ?
            ");
            
            $stmt->bind_param("ii", $perPage, $offset);
            $stmt->execute();
            
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

    // Kreiranje posudbe
    public function createLoan(int $clanID, int $primjerakID): bool {
        try {
            $this->conn->begin_transaction();
            $this->validateIds($clanID, $primjerakID);
            $dostupno = $this->checkAvailability($primjerakID);
            if ($dostupno !== 'dostupno') {
                throw new Exception("Primjerak nije dostupan");
            }
            $stmt = $this->conn->prepare("
            INSERT INTO posudba (ClanID, PrimjerakID, DatumPosudbe)
            VALUES (?, ?, CURDATE())
            ");
            
            $stmt->bind_param("ii", $clanID, $primjerakID);
            $stmt->execute();
            $this->updatePrimjerakStatus($primjerakID, 'posuđeno');
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log($e->getMessage());
            return false;
        }
    }

    // Vraćanje knjige
    public function returnLoan(int $posudbaID): bool {
        try {
            $this->conn->begin_transaction();
            $primjerakID = $this->getPrimjerakId($posudbaID);
            $stmt = $this->conn->prepare("
            UPDATE posudba
            SET DatumVracanja = CURDATE()
            WHERE PosudbaID = ?
            ");
            
            $stmt->bind_param("i", $posudbaID);
            $stmt->execute();
            $this->updatePrimjerakStatus($primjerakID, 'dostupno');
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log($e->getMessage());
            return false;
        }
    }

    // VALIDACIJA
    private function validateIds(int $clanID, int $primjerakID): void {
        $stmt = $this->conn->prepare("SELECT IDClan FROM clan WHERE IDClan = ?");
        $stmt->bind_param("i", $clanID);
        $stmt->execute();
        if ($stmt->get_result()->num_rows === 0) {
            throw new Exception("Nevažeći član");
        }

        $stmt = $this->conn->prepare("SELECT IDPrimjerak FROM primjerak WHERE IDPrimjerak = ?");
        $stmt->bind_param("i", $primjerakID);
        $stmt->execute();

        if ($stmt->get_result()->num_rows === 0) {
            throw new Exception("Nevažeći primjerak");
        }
    }
    private function checkAvailability(int $primjerakID): string {
        $stmt = $this->conn->prepare("
        SELECT Dostupno FROM primjerak WHERE IDPrimjerak = ?
        ");
        $stmt->bind_param("i", $primjerakID);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row['Dostupno'] ?? 'nepoznato';
    }
    private function updatePrimjerakStatus(int $primjerakID, string $status): void {
        $stmt = $this->conn->prepare("
            UPDATE primjerak
            SET Dostupno = ?
            WHERE IDPrimjerak = ?
        ");

        $stmt->bind_param("si", $status, $primjerakID);
        $stmt->execute();
    }

    private function getPrimjerakId(int $posudbaID): int {
        $stmt = $this->conn->prepare("
        SELECT PrimjerakID FROM posudba WHERE PosudbaID = ?
        ");

        $stmt->bind_param("i", $posudbaID);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row['PrimjerakID'] ?? 0;
    }
}
?>