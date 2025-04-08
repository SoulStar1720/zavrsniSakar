<?php
require_once '../includes/db_connection.php';
require_once '../includes/header.php';

echo "<h2>Popis svih članova</h2>";

$sql = "SELECT IDClan, Ime, Prezime, Tip, Email FROM Clan ORDER BY Prezime, Ime";
$result = mysqli_query($conn, $sql);

echo "<table class='table'>
<thead>
<tr>
<th>ID</th>
<th>Ime</th>
<th>Prezime</th>
<th>Tip</th>
<th>Email</th>
<th>Akcije</th>
</tr>
</thead>
<tbody>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
    <td>".htmlspecialchars($row['IDClan'])."</td>
    <td>".htmlspecialchars($row['Ime'])."</td>
    <td>".htmlspecialchars($row['Prezime'])."</td>
    <td>".htmlspecialchars($row['Tip'])."</td>
    <td>".htmlspecialchars($row['Email'])."</td>
    <td>
        <a href='uredi.php?id=".$row['IDClan']."' class='btn btn-sm btn-warning'>Uredi</a>
    </td>
    </tr>";
}

echo "</tbody></table>";

require_once '../includes/footer.php';
?>