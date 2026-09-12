<?php
// Kredensial dapat dioverride lewat environment variable agar tidak perlu
// mengubah kode saat rotasi password; nilai literal di sini hanya fallback.
$hostmysql = getenv('DS_DB_HOST') ?: "localhost";
$username = getenv('DS_DB_USER') ?: "t42590_ds";
$password = getenv('DS_DB_PASSWORD') ?: "REDACTED_ROTATED_PASSWORD";
$database = getenv('DS_DB_NAME') ?: "t42590_digitalsignature";

// Gunakan @ untuk suppress error, lalu cek manual
$conn = @mysqli_connect($hostmysql, $username, $password, $database);

if (!$conn) {
    // Simpan error ke log, tapi jangan tampilkan ke pengguna
    error_log("Database connection failed: " . mysqli_connect_error());
    die("Maaf, terjadi gangguan teknis. Silakan coba lagi nanti."); // Pesan umum
}

// Set charset agar aman
mysqli_set_charset($conn, "utf8");
?>