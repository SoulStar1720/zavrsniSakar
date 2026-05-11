<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db_connection.php';

// Samo prijavljeni korisnici mogu rezervirati
requireLogin();

$knjiga_id = isset($_GET['id']) ? (int)$GET['id'] : 0;
$clan_id = $_SESSION['user_id']; // Pretpostavka da sesija drži ID korisnika/člana
$datum_rezervacije = date('Y-m-d');

if ($knjiga_id > 0) {
    try {
        // Prvo provjeravamo postoji li već aktivna rezervacija za tog člana i tu knjigu
        $check = $conn->prepare("SELECT * FROM rezervacija WHERE ClanID = ? AND KnjigaID = ? AND Status = 'Aktivna'");
        $check->bind_param("ii", $clan_id, $knjiga_id);
        $check->execute();
        
        if ($check->get_result()->num_rows > 0) {
            echo "<script>alert('Već ste rezervirali ovu knjigu!'); window.location.href='index.php';</script>";
            exit();
        }

        // Upis rezervacije
        $stmt = $conn->prepare("INSERT INTO rezervacija (DatumRezervacije, Status, ClanID, KnjigaID) VALUES (?, 'Aktivna', ?, ?)");
        $stmt->bind_param("sii", $datum_rezervacije, $clan_id, $knjiga_id);
        
        if ($stmt->execute()) {
            echo "<script>alert('Knjiga je uspješno rezervirana!'); window.location.href='index.php';</script>";
        } else {
            throw new Exception("Greška pri izvršavanju upita.");
        }
    } catch (Exception $e) {
        echo "Došlo je do pogreške: " . $e->getMessage();
    }
} else {
    header("Location: index.php");
}