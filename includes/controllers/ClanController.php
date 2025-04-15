<?php
class ClanController { 
    private $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }
         // praginacija članova, radai da ako ima puno članova da se vide u stranicama po maksimalno 10 članova, trenutačno nepotrebno ali nek ostane
    public function getAllMembers(int $page = 1, int $perPage = 10): array {
        $offset = ($page - 1) * $perPage;
        
        $stmt = $this->conn->prepare("
            SELECT IDClan, Ime, Prezime, Email, role 
            FROM Clan 
            ORDER BY Prezime, Ime 
            LIMIT ? OFFSET ?
        ");
        $stmt->bind_param("ii", $perPage, $offset);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    // Dohvaćanje člana po ID-u s dodatnom provjerom postojanja
    public function getMemberById(int $id): ?array {
        $stmt = $this->conn->prepare("
            SELECT IDClan, Ime, Prezime, Email, role 
            FROM Clan 
            WHERE IDClan = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $result = $stmt->get_result()->fetch_assoc();
        return $result ?: null;
    }
    // Dodavanje novog člana s validacijom emaila i provjerom duplikata
    public function addMember(array $memberData): bool {
        try {
            if (empty($memberData['email']) || !filter_var($memberData['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Neispravan email format");
            }
            // Provjera postoji li email
            $checkStmt = $this->conn->prepare("SELECT IDClan FROM Clan WHERE Email = ?");
            $checkStmt->bind_param("s", $memberData['email']);
            $checkStmt->execute();
            if ($checkStmt->get_result()->num_rows > 0) {
                throw new Exception("Email već postoji u sustavu");
            }

            $stmt = $this->conn->prepare("
                INSERT INTO Clan 
                (Ime, Prezime, Email, Lozinka, role) 
                VALUES (?, ?, ?, ?, ?)
            ");
            
            $hashedPassword = password_hash($memberData['lozinka'], PASSWORD_BCRYPT);
            
            $stmt->bind_param(
                "sssss",
                $memberData['ime'],
                $memberData['prezime'],
                $memberData['email'],
                $hashedPassword,
                $memberData['role'] ?? 'user'
            );
            
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            error_log("Greška pri dodavanju člana: " . $e->getMessage());
            return false;
        }
    }
    // Ažuriranje člana s opcijom promjene lozinke
    public function updateMember(int $id, array $memberData): bool {
        try {
            $sql = "UPDATE Clan SET 
                    Ime = ?, 
                    Prezime = ?, 
                    Email = ? 
                    " . (isset($memberData['lozinka']) ? ", Lozinka = ?" : "") . "
                    WHERE IDClan = ?";

            $stmt = $this->conn->prepare($sql);
            
            $params = [
                $memberData['ime'],
                $memberData['prezime'],
                $memberData['email']
            ];
            
            if (isset($memberData['lozinka'])) {
                $hashedPassword = password_hash($memberData['lozinka'], PASSWORD_BCRYPT);
                $params[] = $hashedPassword;
            }
            
            $params[] = $id;
            
            $stmt->bind_param(str_repeat("s", count($params)), ...$params);
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            error_log("Greška pri ažuriranju člana: " . $e->getMessage());
            return false;
        }
    }
    // Brisanje člana s provjerom posudbi
    public function deleteMember(int $id): bool {
        try {
            $posudbeStmt = $this->conn->prepare("
                SELECT COUNT(*) FROM Posudba 
                WHERE ClanID = ? AND DatumVracanja IS NULL
            ");
            $posudbeStmt->bind_param("i", $id);
            $posudbeStmt->execute();
            
            if ($posudbeStmt->get_result()->fetch_row()[0] > 0) {
                throw new Exception("Član ima aktivne posudbe");
            }

            $stmt = $this->conn->prepare("
                DELETE FROM Clan 
                WHERE IDClan = ?
            ");
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            error_log("Greška pri brisanju člana: " . $e->getMessage());
            return false;
        }
    }
    // Validirana promjena uloge
    public function changeRole(int $id, string $role): bool {
        try {
            $allowedRoles = ['user', 'admin'];
            if (!in_array($role, $allowedRoles)) {
                throw new Exception("Nedozvoljena uloga");
            }

            $stmt = $this->conn->prepare("
                UPDATE Clan 
                SET role = ? 
                WHERE IDClan = ?
            ");
            $stmt->bind_param("si", $role, $id);
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            error_log("Greška pri promjeni uloge: " . $e->getMessage());
            return false;
        }
    }
    public function countMembers(): int {
        $result = $this->conn->query("SELECT COUNT(*) AS total FROM Clan");
        $row = $result->fetch_assoc();
        return (int) $row['total'];
    }
}
?>