<?php
$hostmysql = "localhost";
$username = "t42590_ds";
$password = "REDACTED_ROTATED_PASSWORDREDACTED_ROTATED_PASSWORD";
$database = "t42590_digitalsignature";
$conn = mysqli_connect($hostmysql, $username, $password, $database);

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