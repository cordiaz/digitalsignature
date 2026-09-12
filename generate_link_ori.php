<html>
<head>
<title>Generate Link</title>
</head>
	<style>
.content {
  max-width: 640px;
  margin: auto;
}
</style>
<body>
	<div class="content">
<h2>Link Berhasil Dibuat & Disimpan</h2>
	<hr>
<a href="index.php" target="_blank">Kembali</a>
<br>
<?php
 
// database connection
include ("connect.php");

// create function for generate random password
function generate_link($len = 8){
 $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
 $pesan = substr( str_shuffle( $chars ), 0, $len );
 return $pesan;
}
 
// insert into database
 $name=$_POST['name'];
 $email=$_POST['email'];
 $alamat=$_POST['address'];
 $kota=$_POST['city'];
// $pesan=$_POST['msg'];
 $pesan = generate_link();

// sql enty data pada tabel
$stmt = $conn->prepare("INSERT INTO tamu (name, email, address, city, msg) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $name, $email, $alamat, $kota, $pesan);

if ($stmt->execute()) {
  //  echo "Pesan telah terkirim!";
} else {
    echo "Error: " . htmlspecialchars($stmt->error);
}

$conn->close();
 // echo "<br>";
 ///echo "Random number is: ".$pesan;
 echo "<br>";
 echo "Link: https://cordiaz.com/digitalsignature/detail.php?msg=".htmlspecialchars($pesan);
 echo "<br>";
 echo "Nama Klien:".htmlspecialchars($name);
 echo "<br>";
?>
		<br>
		<a href="https://www.cordiaz.com/digitalsignature/phpqrcode/index.php" target="_blank">QR Code</a>

		</div>
</body>
</html>