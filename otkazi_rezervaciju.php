<?php
require_once 'includes/auth.php';
require_once 'includes/db_connection.php';

// Osiguranje da je korisnik ulogiran
requireLogin();

// Dohvaćamo ID rezervacije iz URL-a
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$userId = $_SESSION['user_id'];

if ($id > 0) {
    try {
        // Mijenjamo status u 'Otkazano' umjesto da brišemo redak (bolje za evidenciju)
        // Također provjeravamo ClanID da netko ne bi mogao otkazati tuđu rezervaciju preko URL-a
        $stmt = $conn->prepare("UPDATE rezervacija SET Status = 'Otkazano' WHERE IDRezervacija = ? AND ClanID = ?");
        $stmt->bind_param("ii", $id, $userId);
        
        if ($stmt->execute()) {
            echo "<script>alert('Rezervacija je uspješno otkazana.'); window.location.href='profile.php';</script>";
        } else {
            echo "<script>alert('Greška pri otkazivanju.'); window.location.href='profile.php';</script>";
        }
    } catch (Exception $e) {
        echo "Došlo je do pogreške: " . $e->getMessage();
    }
} else {
    header("Location: profile.php");
}
exit();