<h3>Form Pencarian Dengan PHP - RAJA MALIKUL FAJAR</h3>

<form action="index.php" method="get">
<label>Cari :</label>
<input type="text" name="cari">
<input type="submit" value="Cari">
</form>

<?php
if(isset($_GET['cari'])){
$cari = $_GET['cari'];
echo "< b>Hasil pencarian : ".$cari."< /b>";
}
?>

<table border="1">
<tr>
<th>No</th>
<th>Nama</th>
</tr>
<?php
if(isset($_GET['cari'])){
$koneksi = mysqli_connect('localhost', 't42590_ds', '2RgH5e4TaUkz7MnT', 't42590_digitalsignature');
$cari = $_GET['cari'];
$data = mysqli_query("select * from tamu where name like '%".$cari."%'");
}else{
$data = mysqli_query("select * from tamu");
}
$no = 1;
while($d = mysqli_fetch_array($data)){
?>
<tr>
<td><?php echo $no++; ?></td>
<td><?php echo $d['name']; ?></td>
</tr>
<?php } ?>
</table>