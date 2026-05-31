<?php
class PosudbaController {
    private $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    // Dohvaćanje svih posudbi
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
        $this->validateLoanData($clanID, $primjerakID);

        // Provjera je li primjerak dostupan (korištenje polja 'status')
        if ($this->checkAvailability($primjerakID) !== 'dostupno') {
            throw new Exception("Primjerak nije dostupan za posudbu.");
        }

        $datumPosudbe = date('Y-m-d');
        
        $this->conn->begin_transaction();

        try {
            // Unos u tablicu posudba
            $stmt = $this->conn->prepare("
                INSERT INTO posudba (ClanID, PrimjerakID, DatumPosudbe)
                VALUES (?, ?, ?)
            ");
            $stmt->bind_param("iis", $clanID, $primjerakID, $datumPosudbe);
            $stmt->execute();

            // Ažuriranje statusa primjerka na 'posuđeno'
            $this->updatePrimjerakStatus($primjerakID, 'posuđeno');

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }

    // Vraćanje knjige
    public function returnBook(int $posudbaID): bool {
        $primjerakID = $this->getPrimjerakId($posudbaID);
        $datumVracanja = date('Y-m-d');

        $this->conn->begin_transaction();

        try {
            // Ažuriranje datuma vraćanja u tablici posudba
            $stmt = $this->conn->prepare("
                UPDATE posudba 
                SET DatumVracanja = ? 
                WHERE IDPosudba = ?
            ");
            $stmt->bind_param("si", $datumVracanja, $posudbaID);
            $stmt->execute();

            // Vraćanje statusa primjerka na 'dostupno'
            $this->updatePrimjerakStatus($primjerakID, 'dostupno');

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }

    private function validateLoanData(int $clanID, int $primjerakID): void {
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
        $stmt = $this->conn->prepare("SELECT status FROM primjerak WHERE IDPrimjerak = ?");
        $stmt->bind_param("i", $primjerakID);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row['status'] ?? 'nepoznato';
    }

    private function updatePrimjerakStatus(int $primjerakID, string $status): void {
        $stmt = $this->conn->prepare("UPDATE primjerak SET status = ? WHERE IDPrimjerak = ?");
        $stmt->bind_param("si", $status, $primjerakID);
        $stmt->execute();
    }

    private function getPrimjerakId(int $posudbaID): int {
        $stmt = $this->conn->prepare("SELECT PrimjerakID FROM posudba WHERE IDPosudba = ?");
        $stmt->bind_param("i", $posudbaID);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        if (!$result) {
            throw new Exception("Posudba nije pronađena.");
        }
        
        return (int)$result['PrimjerakID'];
    }
}