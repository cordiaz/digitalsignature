<?php
// Memuat file rahasia opsional yang disimpan SATU FOLDER DI ATAS document root
// (mis. /secrets.php di sisi hosting), sehingga kredensial produksi (DB & login)
// tidak perlu disimpan di git maupun bergantung pada dukungan environment
// variable dari panel hosting. File itu cukup memanggil putenv(), lalu semua
// pemanggil getenv() di connect.php/action-login.php otomatis memakainya.
// Aman diakses lewat browser: path-nya di luar document root situs.
$secrets_file = ($_SERVER['DOCUMENT_ROOT'] ?? __DIR__) . '/../secrets.php';
if (is_file($secrets_file)) {
    include_once $secrets_file;
}
