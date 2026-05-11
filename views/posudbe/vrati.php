<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db_connection.php';
require_once __DIR__ . '/../../includes/controllers/PosudbaController.php';

// Dozvoli pristup svima koji su prijavljeni
requireLogin();

$posudbaController = new PosudbaController($conn);

// Provjeravamo šalje li se ID putem POST (iz profile.php) ili GET (iz admin panela)
$posudbaID = 0;
if (isset($_POST['posudba_id'])) {
    $posudbaID = (int)$_POST['posudba_id'];
} elseif (isset($_GET['id'])) {
    $posudbaID = (int)$_GET['id'];
}

if ($posudbaID > 0) {
    try {
        // Poziv funkcije returnBook iz kontrolera
        if ($posudbaController->returnBook($posudbaID)) {
            echo "<script>
                    alert('Knjiga je uspješno vraćena!'); 
                    window.location.href='../../profile.php';
                  </script>";
            exit();
        } else {
            throw new Exception("Greška prilikom vraćanja knjige u bazi.");
        }
    } catch (Exception $e) {
        echo "<script>
                alert('Greška: " . $e->getMessage() . "'); 
                window.location.href='../../profile.php';
              </script>";
        exit();
    }
} else {
    header("Location: ../../profile.php");
    exit();
}