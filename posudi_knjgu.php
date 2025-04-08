<?php
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clanID = $_POST['clanID'];
    $primjerakID = $_POST['primjerakID'];
    $datumPosudbe = date('d-m-Y');
    
    $sql = "UPDATE primjerak SET Dostupno = 'posuđeno', ClanID = '$clanID', DatumPosudbe = '$datumPosudbe' WHERE IDPrimjerak = '$primjerakID'";
    mysqli_query($conn, $sql);
    
    $sql = "INSERT INTO posudba (ClanID, PrimjerakID, DatumPosudbe, DatumVracanja) VALUES ('$clanID', '$primjerakID', '$datumPosudbe', NULL)";
    mysqli_query($conn, $sql);
    
     echo "<p style='color:green;'>Knjiga je uspješno posuđena!</p>";
}

echo "<h2>Posudba knjige</h2>
<form method='post'>
    <label for='clanID'>Član:</label>
    <select name='clanID' required>";
    
$sql = "SELECT IDClan, CONCAT(Prezime, ' ', Ime) AS ImePrezime FROM clan ORDER BY Prezime";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    echo "<option value='".$row['IDClan']."'>".$row['ImePrezime']."</option>";
}

echo "</select><br><br>
    <label for='primjerakID'>Dostupni primjerci:</label>
    <select name='primjerakID' required>";
    
$sql = "SELECT p.IDPrimjerak, CONCAT(v.naslov, ' (', v.autor, ')') AS Knjiga 
        FROM primjerak p
        JOIN vrstaliterature v ON p.LiteraturaID = v.IDLiteratura
        WHERE p.Dostupno = 'dostupno'";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    echo "<option value='".$row['IDPrimjerak']."'>".$row['Knjiga']."</option>";
}

echo "</select><br><br>
    <input type='submit' value='Posudi knjigu'>
</form>";

mysqli_close($conn);
?>