<?php
// Kredensial dapat dioverride lewat environment variable (atau file secrets.php
// di luar document root, lihat load_secrets.php) agar tidak perlu mengubah kode
// saat rotasi password; nilai literal di sini hanya fallback.
include_once __DIR__ . '/load_secrets.php';
$hostmysql = getenv('DS_DB_HOST') ?: "localhost";
$username = getenv('DS_DB_USER') ?: "t42590_ds";
$password = getenv('DS_DB_PASSWORD') ?: "REDACTED_ROTATED_PASSWORD";
$database = getenv('DS_DB_NAME') ?: "t42590_digitalsignature";

// Sejak PHP 8.1, mysqli default melempar exception saat gagal konek
// (operator @ tidak meredam exception, hanya warning), jadi harus try/catch
// agar pesan error asli (berisi username/host) tidak bocor ke pengguna.
mysqli_report(MYSQLI_REPORT_OFF);
$conn = mysqli_connect($hostmysql, $username, $password, $database);

if (!$conn) {
    // Simpan error ke log, tapi jangan tampilkan ke pengguna
    error_log("Database connection failed: " . mysqli_connect_error());
    die("Maaf, terjadi gangguan teknis. Silakan coba lagi nanti."); // Pesan umum
}

// Set charset agar aman
mysqli_set_charset($conn, "utf8");
?>