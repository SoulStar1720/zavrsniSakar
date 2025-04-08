<?php
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $primjerakID = $_POST['primjerakID'];
    $datumVracanja = date('Y-m-d');
    
    $sql = "UPDATE primjerak SET Dostupno = 'dostupno', ClanID = NULL, DatumPosudbe = NULL WHERE IDPrimjerak = '$primjerakID'";
    mysqli_query($conn, $sql);
    
    $sql = "UPDATE posudba SET DatumVracanja = '$datumVracanja' WHERE PrimjerakID = '$primjerakID' AND DatumVracanja IS NULL";
    mysqli_query($conn, $sql);
    
    echo "<p style='color:green;'>Knjiga je uspješno vraćena!</p>";
}

echo "<h2>Vraćanje knjige</h2>
<form method='post'>
    <label for='primjerakID'>Posuđeni primjerci:</label>
    <select name='primjerakID' required>";
    
$sql = "SELECT p.IDPrimjerak, CONCAT(v.naslov, ' (', v.autor, ') - ', c.Prezime, ' ', c.Ime) AS Knjiga 
        FROM primjerak p
        JOIN vrstaliterature v ON p.LiteraturaID = v.IDLiteratura
        JOIN clan c ON p.ClanID = c.IDClan
        WHERE p.Dostupno = 'posuđeno'";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    echo "<option value='".$row['IDPrimjerak']."'>".$row['Knjiga']."</option>";
}

echo "</select><br><br>
    <input type='submit' value='Vrati knjigu'>
</form>";

mysqli_close($conn);
?>