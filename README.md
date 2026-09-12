# Digital Signature Link Generator

Aplikasi PHP sederhana untuk membuat link "digital signature" unik per klien, menyimpannya di database, dan menghasilkan QR code yang mengarah ke halaman detail klien tersebut.

## Alur Kerja

1. Login di `login.php` (lihat [Kredensial](#kredensial-login--database)).
2. Isi form klien di `index.php` (nama, email, keterangan, kota).
3. `generate_link.php` menyimpan data ke tabel `tamu`, membuat kode acak (`msg`), lalu mengarahkan ke `phpqrcode/index.php` untuk membuat QR code dari link `detail.php?msg=<kode>`.
4. Siapa pun yang memindai QR code atau membuka link tersebut akan melihat detail klien di `detail.php`.

## Struktur Proyek

| File / Folder | Keterangan |
|---|---|
| `index.php` | Form input data klien (butuh login). |
| `generate_link.php` | Simpan data ke DB, generate kode acak, redirect ke QR generator. |
| `detail.php` | Menampilkan detail klien berdasarkan parameter `msg`. |
| `connect.php` | Koneksi MySQL (kredensial via env var / `secrets.php`, lihat di bawah). |
| `load_secrets.php` | Memuat `secrets.php` opsional dari luar document root. |
| `login.php`, `action-login.php`, `action-logout.php` | Autentikasi sederhana berbasis session (kredensial hardcoded/env var, bukan tabel user). |
| `session/` | Salinan alur login/app terpisah dengan kredensial berbeda. |
| `phpqrcode/` | Library pihak ketiga untuk generate QR code (di-vendor langsung ke repo ini). |
| `passwd-generator/` | Utilitas generate password terpisah. |
| `ds-ori/` | Salinan awal/referensi dari fitur utama (tidak dipakai langsung). |
| `sql.sql` | Skema tabel `tamu`. |
| `*.ori`, `*-ori.php` | Versi lama/referensi yang sudah tidak dipakai di alur aktif. |

## Instalasi

1. Import skema database:
   ```sql
   source sql.sql;
   ```
2. Sesuaikan nama database/host/user sesuai environment Anda.
3. Deploy seluruh isi repo ke document root hosting.
4. Pastikan folder `phpqrcode/cache/` dan `phpqrcode/temp/` dapat ditulis oleh web server (untuk cache mask QR code dan file QR sementara).

## Kredensial Login & Database

Kredensial **tidak boleh** disimpan sebagai nilai literal di kode. Prioritas pengambilan nilai:

1. Environment variable (jika didukung panel hosting):
   - `DS_DB_HOST`, `DS_DB_USER`, `DS_DB_PASSWORD`, `DS_DB_NAME`
   - `DS_LOGIN_USERNAME`, `DS_LOGIN_PASSWORD_HASH`
2. File `secrets.php` di **satu folder di atas document root** (lihat `load_secrets.php`), berisi pemanggilan `putenv()`, misalnya:
   ```php
   <?php
   putenv('DS_DB_PASSWORD=...');
   putenv('DS_LOGIN_PASSWORD_HASH=...');
   ```
3. Fallback di kode — hanya nilai placeholder, **bukan** kredensial asli.

File `secrets.php` **jangan pernah** di-commit ke git (sudah ada di `.gitignore`).

Untuk membuat hash password login baru:
```
php -r "echo password_hash('password_baru_anda', PASSWORD_BCRYPT), PHP_EOL;"
```

## Catatan Keamanan

- `connect.php` mematikan mode exception mysqli (`mysqli_report(MYSQLI_REPORT_OFF)`) agar kegagalan koneksi ditangani secara manual dan tidak membocorkan detail internal (username/host) ke pengguna.
- Semua query menggunakan prepared statement (`mysqli::prepare` + `bind_param`).
- Output ke HTML di-escape dengan `htmlspecialchars()`.
