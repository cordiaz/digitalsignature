<?php 
    include 'connect.php';
?>

<h2>Detail Number Digital Signature</h2>

<form action="cari.php" method="get">
    <label>Cari :</label>
    <input type="text" name="cari">
    <input type="submit" value="Cari">
</form>
<?php 
    if(isset($_GET['cari'])){
        $cari = $_GET['cari'];
        echo "<b>Hasil pencarian : ".htmlspecialchars($cari)."</b>";
		echo "<br>";
    }
?>
<table>
    <tr>
        <th>No</th>
        <th>Nama Klien</th>
		<th>Keterangan</th>
        <th>Timestamp</th>
    </tr>
<?php 
    if(isset($_GET['cari'])){
        $cari = $_GET['cari'];
        $like = "%" . $cari . "%";
        $stmt = $conn->prepare("select * from tamu where msg like ?");
        $stmt->bind_param("s", $like);
        $stmt->execute();
        $data = $stmt->get_result();
    }
    else{
        $data = mysqli_query($conn, "select * from tamu");
    }
    $no = 1;
    while($d = mysqli_fetch_array($data)){
 ?>
    <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo htmlspecialchars($d['name']); ?></td>
		<td><?php echo htmlspecialchars($d['address']); ?></td>
		<td><?php echo htmlspecialchars($d['timestamp']); ?></td>
    </tr>
<?php
    } ?>
</table>