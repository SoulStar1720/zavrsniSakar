<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db_connection.php';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/controllers/PosudbaController.php';

requireAdmin();

$posudbaController = new PosudbaController($conn);
$error = '';

if (isset($_GET['id'])) {
    try {
        if ($posudbaController->returnLoan((int)$_GET['id'])) {
            $_SESSION['success'] = "Knjiga je uspješno vraćena!";
        } else {
            $_SESSION['error'] = "Greška prilikom vraćanja knjige";
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
}

header("Location: index.php");
exit();
?>