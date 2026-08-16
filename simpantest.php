<html>
<head>
<title>Contact</title>
</head>
<body>
<h1>Berhasil disimpan</h1>
<a href="index.php">Kembali ke Awal</a>
<br>

<?php
include ("connect.php");
	
// create function for generate random password
function generate_password($len = 8){
 $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
 $pesan = substr( str_shuffle( $chars ), 0, $len );
 return $pesan;
}
 
$nama=$_POST['name'];
$email=$_POST['email'];
$alamat=$_POST['address'];
$kota=$_POST['city'];
$pasan = generate_password();
$pesan=$_POST['msg'];

// sql entry data pada tabel
$sql = "INSERT INTO tamu (name, email, address, city, msg)
VALUES ('$nama','$email','$alamat','$kota','$pesan')";

if ($conn->query($sql) === TRUE) {
    echo "Pesan telah terkirim!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// tampilkan
 echo "Your random number is: <br>".$password;
$conn->close();
?>
</body>
</html>