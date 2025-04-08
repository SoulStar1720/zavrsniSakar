<?php
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'knjiznica';

$conn = mysqli_connect($server, $username, $password, $database);

if (!$conn) {
    die("Greška pri spajanju na bazu: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");
?>