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
$conn = mysqli_connect($hostmysql, $username, $password, $database) or die($conn); 
 
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
$sql = "INSERT INTO tamu (name, email, address, city, msg)
VALUES ('$name','$email','$alamat','$kota','$pesan')";

if ($conn->query($sql) === TRUE) {
  //  echo "Pesan telah terkirim!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
 // echo "<br>";
 ///echo "Random number is: ".$pesan;
 echo "<br>";
 echo "Link: https://cordiaz.com/digitalsignature/detail.php?msg=".$pesan;
 echo "<br>";
 echo "Nama Klien:".$name;
 echo "<br>";
?>
		<br>
		<a href="https://www.cordiaz.com/digitalsignature/phpqrcode/index.php" target="_blank">QR Code</a>

		</div>
</body>
</html>