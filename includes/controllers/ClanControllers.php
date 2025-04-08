<?php
class ClanController {
    private $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    /*
    public function getAllMembers(int $page = 1, int $perPage = 10): array {
        $offset = ($page - 1) * $perPage;
        
        $stmt = $this->conn->prepare("
            SELECT IDClan, Ime, Prezime, Tip, Email, role 
            FROM Clan 
            ORDER BY Prezime, Ime 
            LIMIT ? OFFSET ?
        ");
        $stmt->bind_param("ii", $perPage, $offset);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }*/

    //Dohvaćanje člana po 
    public function getMemberById(int $id): ?array {
        $stmt = $this->conn->prepare("
            SELECT IDClan, Ime, Prezime, Tip, Email, role 
            FROM Clan 
            WHERE IDClan = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $result = $stmt->get_result()->fetch_assoc();
        return $result ?: null;
    }

    //Dodavalje novog člana
    public function addMember(array $memberData): bool {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO Clan 
                (Ime, Prezime, Tip, Email, Lozinka, role) 
                VALUES (?, ?, ?, ?, ?, 'user')
            ");
            
            $hashedPassword = password_hash($memberData['lozinka'], PASSWORD_BCRYPT);
            
            $stmt->bind_param(
                "sssss",
                $memberData['ime'],
                $memberData['prezime'],
                $memberData['tip'],
                $memberData['email'],
                $hashedPassword
            );
            
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            error_log("Greška pri dodavanju člana: " . $e->getMessage());
            return false;
        }
    }

    //Ažuriranje člana
    public function updateMember(int $id, array $memberData): bool {
        try {
            $stmt = $this->conn->prepare("
                UPDATE Clan 
                SET Ime = ?, Prezime = ?, Tip = ?, Email = ? 
                WHERE IDClan = ?
            ");
            
            $stmt->bind_param(
                "ssssi",
                $memberData['ime'],
                $memberData['prezime'],
                $memberData['tip'],
                $memberData['email'],
                $id
            );
            
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            error_log("Greška pri ažuriranju člana: " . $e->getMessage());
            return false;
        }
    }

    //brisanje člana
    public function deleteMember(int $id): bool {
        try {
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

    //pretraživanje članova
    public function searchMembers(string $query, int $page = 1, int $perPage = 10): array {
        $offset = ($page - 1) * $perPage;
        $searchTerm = "%$query%";
        
        $stmt = $this->conn->prepare("
            SELECT IDClan, Ime, Prezime, Tip, Email 
            FROM Clan 
            WHERE CONCAT(Ime, ' ', Prezime) LIKE ? 
            ORDER BY Prezime, Ime 
            LIMIT ? OFFSET ?
        ");
        $stmt->bind_param("sii", $searchTerm, $perPage, $offset);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    //admin mjenja ulogu članovima
    public function changeRole(int $id, string $role): bool {
        try {
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
}
?>