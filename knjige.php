<?php
require_once 'db_connection.php';

echo "<h2>Popis svih knjiga</h2>";

$sql = "SELECT * FROM vrstaliterature ORDER BY autor, naslov";
$result = mysqli_query($conn, $sql);

echo "<table border='1'>
<tr>
<th>ID</th>
<th>Vrsta</th>
<th>Autor</th>
<th>Naslov</th>
<th>ISBN</th>
<th>Izdavač</th>
<th>Primjeraka</th>
</tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
    <td>".$row['IDLiteratura']."</td>
    <td>".$row['vrsta_literature']."</td>
    <td>".$row['autor']."</td>
    <td>".$row['naslov']."</td>
    <td>".$row['ISBN_broj']."</td>
    <td>".$row['izdavac']."</td>
    <td>".$row['broj_primjeraka']."</td>
    </tr>";
}

echo "</table>";

mysqli_close($conn);
?>
