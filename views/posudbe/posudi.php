<?php
require_once '../includes/db_connection.php';
require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clanID = $_POST['clanID'];
    $primjerakID = $_POST['primjerakID'];
    $datumPosudbe = date('Y-m-d');
    
    // Ažuriraj primjerak
    $sql = "UPDATE primjerak SET 
            Dostupno = 'posuđeno', 
            ClanID = '$clanID', 
            DatumPosudbe = '$datumPosudbe' 
            WHERE IDPrimjerak = '$primjerakID'";
    mysqli_query($conn, $sql);
    
    // Dodaj u posudbe
    $sql = "INSERT INTO posudba (ClanID, PrimjerakID, DatumPosudbe) 
            VALUES ('$clanID', '$primjerakID', '$datumPosudbe')";
    mysqli_query($conn, $sql);
    
    $_SESSION['success'] = "Knjiga je uspješno posuđena!";
    header("Location: index.php");
    exit();
}

echo "<h2>Posudba knjige</h2>
<form method='post'>
    <div class='form-group'>
        <label for='clanID'>Član:</label>
        <select name='clanID' class='form-control' required>";
        
$sql = "SELECT IDClan, CONCAT(Prezime, ' ', Ime) AS ImePrezime FROM clan ORDER BY Prezime";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    echo "<option value='".$row['IDClan']."'>".$row['ImePrezime']."</option>";
}

echo "</select>
    </div>
    
    <div class='form-group'>
        <label for='primjerakID'>Dostupni primjerci:</label>
        <select name='primjerakID' class='form-control' required>";
        
$sql = "SELECT p.IDPrimjerak, CONCAT(v.naslov, ' (', v.autor, ') - ID:', p.IDPrimjerak) AS Knjiga 
        FROM primjerak p
        JOIN vrstaliterature v ON p.LiteraturaID = v.IDLiteratura
        WHERE p.Dostupno = 'dostupno'";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    echo "<option value='".$row['IDPrimjerak']."'>".$row['Knjiga']."</option>";
}

echo "</select>
    </div>
    
    <button type='submit' class='btn btn-primary'>Posudi knjigu</button>
</form>";

require_once '../includes/footer.php';
?>