<html>
<!--
*gatewan.com
*Wawan Beneran
*ENGINE BUKU TAMU (Untuk Action Tombol "SEND")
-->
<head>
<title>Contact</title>
</head>
<body>
<h1>BUKU TAMU</h1>
<a href="index.php"> Kembali ke Buku Tamu</a>
<br>
<h2>BUKU TAMU</h2>
<a href="tampil.php"> Lihat Buku Tamu</a>
<hr size=1>

<?php
include ("connect.php");
$nama=$_POST['name'];
$email=$_POST['email'];
$alamat=$_POST['address'];
$kota=$_POST['city'];
$pesan=$_POST['msg'];

// sql entry data pada tabel
$stmt = $conn->prepare("INSERT INTO tamu (name, email, address, city, msg) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $nama, $email, $alamat, $kota, $pesan);

if ($stmt->execute()) {
    echo "Pesan telah terkirim!";
} else {
    echo "Error: " . htmlspecialchars($stmt->error);
}

$conn->close();
?>
</body>
</html>