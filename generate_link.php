<?php
session_start();
require_once __DIR__ . '/csrf.php';
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Tolak submit tanpa CSRF token yang valid (mis. dari form asing / CSRF attack)
if (!csrf_verify($_POST['csrf_token'] ?? '')) {
    header("Location: index.php");
    exit;
}

// Proses penyimpanan data ke database (diletakkan di awal sebelum output HTML)
// database connection - gunakan path yang benar (connect.php ada di folder yang sama)
include __DIR__ . "/connect.php";

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
$stmt = $conn->prepare("INSERT INTO tamu (name, email, address, city, msg) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $name, $email, $alamat, $kota, $pesan);

if ($stmt->execute()) {
    $insert_success = true;
} else {
    $insert_success = false;
    $error_msg = $stmt->error;
}
$stmt->close();

$conn->close();

// Base URL diambil dari request saat ini, bukan hardcoded, supaya tidak
// meleset kalau domain/path deployment berubah (mis. subdomain vs subfolder).
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$base_url = $scheme . '://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

// ** PERUBAHAN DI SINI **
// Isi QR Code HANYA link (tanpa nama klien)
$qr_data = $base_url . "/detail.php?msg=" . $pesan;
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
            echo "Link: " . htmlspecialchars($base_url) . "/detail.php?msg=" . htmlspecialchars($pesan);
            echo "<br>";
            echo "Nama Klien: " . htmlspecialchars($name);
            echo "<br>";
        } else {
            echo "Error: " . htmlspecialchars($error_msg) . "<br>";
        }
        ?>
        <br>
        <a href="phpqrcode/index.php" target="_blank">QR Code</a>
    </div>

    <?php if ($insert_success): ?>
    <script>
        // ** PERUBAHAN DI SINI **
        // Kirim hanya link ke QR code (tanpa nama)
        var qrData = "<?php echo $base_url; ?>/detail.php?msg=<?php echo $pesan; ?>";
        window.location.href = "phpqrcode/index.php?qr_data=" + encodeURIComponent(qrData);
    </script>
    <?php endif; ?>
</body>
</html>