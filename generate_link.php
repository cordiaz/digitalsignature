<?php
// Proses penyimpanan data ke database (diletakkan di awal sebelum output HTML)
// database connection - gunakan path yang benar (connect.php ada di folder yang sama)
include __DIR__ . "/connect.php";

// Gunakan variabel dari connect.php yang sudah di-include
$conn = mysqli_connect($hostmysql, $username, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// create function for generate random password
function generate_link($len = 8) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $pesan = substr(str_shuffle($chars), 0, $len);
    return $pesan;
}

// insert into database
$name = $_POST['name'];
$email = $_POST['email'];
$alamat = $_POST['address'];
$kota = $_POST['city'];
$pesan = generate_link();

// sql entry data pada tabel
$sql = "INSERT INTO tamu (name, email, address, city, msg)
VALUES ('$name','$email','$alamat','$kota','$pesan')";

if ($conn->query($sql) === TRUE) {
    $insert_success = true;
} else {
    $insert_success = false;
    $error_msg = $conn->error;
}

$conn->close();

// ** PERUBAHAN DI SINI **
// Isi QR Code HANYA link (tanpa nama klien)
$qr_data = "https://cordiaz.com/digitalsignature/detail.php?msg=" . $pesan;
?>
<html>
<head>
<title>Generate Link</title>
<style>
.content {
    max-width: 640px;
    margin: auto;
}
</style>
</head>
<body>
    <div class="content">
        <h2>Link Berhasil Dibuat & Disimpan</h2>
        <hr>
        <a href="index.php" target="_blank">Kembali</a>
        <br>
        <?php
        if ($insert_success) {
            echo "<br>";
            echo "Link: https://cordiaz.com/digitalsignature/detail.php?msg=" . $pesan;
            echo "<br>";
            echo "Nama Klien: " . $name;
            echo "<br>";
        } else {
            echo "Error: " . $error_msg . "<br>";
        }
        ?>
        <br>
        <a href="phpqrcode/index.php" target="_blank">QR Code</a>
    </div>

    <?php if ($insert_success): ?>
    <script>
        // ** PERUBAHAN DI SINI **
        // Kirim hanya link ke QR code (tanpa nama)
        var qrData = "https://cordiaz.com/digitalsignature/detail.php?msg=<?php echo $pesan; ?>";
        window.location.href = "phpqrcode/index.php?qr_data=" + encodeURIComponent(qrData);
    </script>
    <?php endif; ?>
</body>
</html>