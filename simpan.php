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