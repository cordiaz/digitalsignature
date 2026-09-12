<h3>Form Pencarian Dengan PHP - RAJA MALIKUL FAJAR</h3>

<form action="index.php" method="get">
<label>Cari :</label>
<input type="text" name="cari">
<input type="submit" value="Cari">
</form>

<?php
include 'connect.php';
if(isset($_GET['cari'])){
$cari = $_GET['cari'];
echo "< b>Hasil pencarian : ".htmlspecialchars($cari)."< /b>";
}
?>

<table border="1">
<tr>
<th>No</th>
<th>Nama</th>
</tr>
<?php
if(isset($_GET['cari'])){
$cari = $_GET['cari'];
$like = "%" . $cari . "%";
$stmt = $conn->prepare("select * from tamu where name like ?");
$stmt->bind_param("s", $like);
$stmt->execute();
$data = $stmt->get_result();
}else{
$data = mysqli_query($conn, "select * from tamu");
}
$no = 1;
while($d = mysqli_fetch_array($data)){
?>
<tr>
<td><?php echo $no++; ?></td>
<td><?php echo htmlspecialchars($d['name']); ?></td>
</tr>
<?php } ?>
</table>