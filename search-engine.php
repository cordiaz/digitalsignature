<?php
include "connect.php";
$name= $_POST['msg']; //get the nama value from form
$koneksi = mysqli_connect('localhost', 't42590_ds', '2RgH5e4TaUkz7MnT', 't42590_digitalsignature');
$sql = "SELECT * FROM tamu where msg like '%$msg%' "; //query to get the search result
$result = mysqli_query($koneksi, $sql); //execute the query $sql
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
<td>".$data['name']."</td>
<td>".$data['address']."</td>
<td>".$data['msg']."</td>
<td>".$data['timestamp']."</td>
</tr>";
}
echo "</table>";
mysqli_free_result($result);
mysqli_close($koneksi);
?>