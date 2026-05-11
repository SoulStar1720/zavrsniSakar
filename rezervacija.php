<?php
require_once 'includes/auth.php';
require_once 'includes/db_connection.php';

// Samo prijavljeni korisnici mogu rezervirati
requireLogin();

$knjigaID = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$clanID = $_SESSION['user_id'];

if ($knjigaID > 0) {
    try {
        // 1. Provjera postoji li već aktivna rezervacija istog člana za tu knjigu
        $check = $conn->prepare("SELECT * FROM rezervacija WHERE ClanID = ? AND KnjigaID = ? AND Status = 'Aktivna'");
        $check->bind_param("ii", $clanID, $knjigaID);
        $check->execute();
        
        if ($check->get_result()->num_rows > 0) {
            echo "<script>alert('Već imate aktivnu rezervaciju za ovu knjigu.'); window.location.href='index.php';</script>";
            exit();
        }

        // 2. Unos nove rezervacije
        $datum = date('Y-m-d');
        $status = 'Aktivna';
        
        $stmt = $conn->prepare("INSERT INTO rezervacija (ClanID, KnjigaID, DatumRezervacije, Status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $clanID, $knjigaID, $datum, $status);

        if ($stmt->execute()) {
            echo "<script>alert('Knjiga uspješno rezervirana!'); window.location.href='profile.php';</script>";
        } else {
            throw new Exception("Greška pri upisu u bazu.");
        }

    } catch (Exception $e) {
        echo "<script>alert('Greška: " . $e->getMessage() . "'); window.location.href='index.php';</script>";
    }
} else {
    header("Location: index.php");
}