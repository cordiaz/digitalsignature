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
        echo "<b>Hasil pencarian : ".$cari."</b>";
		echo "<br>";
    }
?>
<table>
    <tr>
        <th>No</th>
        <th align="left">Nama Klien</th>
    </tr>
<?php 
    if(isset($_GET['cari'])){
       $koneksi = mysqli_connect('localhost', 't42590_ds', '2RgH5e4TaUkz7MnT', 't42590_digitalsignature');
		$cari = $_GET['cari'];
        $data = mysqli_query($koneksi, "select * from tamu where msg like '%".$cari."%' "); 
    }
    else{
        $data = mysqli_query("select * from tamu"); 
    }
    $no = 1;
    while($d = mysqli_fetch_array($data)){
?>
    <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo $d['name']; ?></td>
    </tr>
<?php
    } ?>
<table>
