<?php
include "connect.php";
$name = $_POST['name']; //get the search value from form (matches search.php's "name" field)
$like = "%" . $name . "%";
$stmt = $conn->prepare("SELECT * FROM tamu where msg like ?"); //query to get the search result
$stmt->bind_param("s", $like);
$stmt->execute();
$result = $stmt->get_result();
echo "<center>";
echo "<h2>Hasil Pencarian</h2>";
echo "<table border='1' cellpadding='5' cellspacing='8'>";
echo "
<tr bgcolor='orange'>
<td>Nama Klien</td>
<td>Keterangan</td>
<td>Kode</td>
<td>Timestamp</td>
</tr>";
while ($data = mysqli_fetch_array($result)) {  //fetch the result from query into an array
echo "
<tr>
<td>".htmlspecialchars($data['name'])."</td>
<td>".htmlspecialchars($data['address'])."</td>
<td>".htmlspecialchars($data['msg'])."</td>
<td>".htmlspecialchars($data['timestamp'])."</td>
</tr>";
}
echo "</table>";
mysqli_free_result($result);
$conn->close();
?>