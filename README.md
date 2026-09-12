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

## Progress / Riwayat Perubahan

Ringkasan pekerjaan yang sudah dilakukan sampai kondisi saat ini, urut dari yang paling lama:

1. **Perbaikan keamanan awal** — SQL injection, XSS, auth bypass, dan kredensial hardcoded diperbaiki (query jadi prepared statement, output di-escape, kredensial mulai memakai `getenv()`).
2. **Mekanisme `secrets.php`** — `load_secrets.php` ditambahkan agar kredensial asli (DB & login) bisa disimpan di satu file *di luar document root* (`secrets.php`), bukan hardcoded di kode maupun bergantung pada dukungan environment variable dari panel hosting.
3. **Perbaikan bug koneksi DB** — sejak PHP 8.1, `mysqli_connect()` melempar exception saat gagal (bukan sekadar `false`), sehingga error asli (berisi username/host) sempat bocor ke halaman. Ditambahkan `mysqli_report(MYSQLI_REPORT_OFF)` agar kembali ke penanganan error manual yang aman.
4. **Insiden password ter-commit & perbaikannya** — password DB asli sempat ter-commit langsung ke `connect.php` saat rotasi password di server. Sudah dikembalikan ke placeholder; password tersebut sudah dianggap ter-expose di git history dan sebaiknya dirotasi ulang jika belum.
5. **Vendoring `phpqrcode`** — library QR code yang sebelumnya hanya ada manual di server (tidak tercatat di git, menyebabkan 404 setelah deploy ulang) sekarang di-vendor langsung ke repo ini (`phpqrcode/`) supaya ikut ter-deploy otomatis.
6. **Restore `generate_link.php`** — sempat terhapus tanpa sadar (bersamaan dengan vendoring `phpqrcode`), padahal ini file inti yang dipanggil form di `index.php` untuk simpan data + generate kode + redirect ke QR generator. Sudah dikembalikan.
7. **Perbaikan link/QR yang salah domain** — `generate_link.php`, `detail.php`, `phpqrcode/index.php`, `detailtest.php`, dan `passwd-generator/generate_link.php` sebelumnya hardcode base URL yang salah (`https://cordiaz.com/digitalsignature` atau `https://www.cordiaz.com/digitalsignature`), padahal aplikasi berjalan di root domain `https://digitalsignature.cordiaz.com`. Semua sudah diganti membangun base URL secara dinamis dari request saat ini, supaya tidak salah lagi kalau domain/path deployment berubah.
8. **Redesain UI** — `login.php`, `index.php`, `detail.php`, dan `phpqrcode/index.php` diberi tampilan kartu modern (gradient background, input & tombol bergaya konsisten) menggantikan tabel HTML polos, tanpa mengubah nama field maupun alur PHP yang sudah ada.
9. **Penyesuaian opsi QR code** — panel "Generate QR Code manual" di `phpqrcode/index.php` di-collapse (tertutup) secara default, dengan nilai default ECC = `Q` dan Size = `4`.
10. **Bersih-bersih kode tidak terpakai** — `detailtest.php` dan folder `passwd-generator/` dihapus karena tidak lagi direferensikan di alur aktif manapun.

### Yang Masih Perlu Diperhatikan

- Kolom `timestamp` dipakai di `detail.php` tapi tidak ada di skema `sql.sql` — perlu dipastikan apakah kolom ini memang ada di database production atau perlu ditambahkan.
- File-file `*-ori.php`/`*.ori` (mis. `index.php.ori`, `simpan.php.ori`, `ds-ori/`) adalah kode lama/referensi yang sudah tidak dipakai di alur aktif — kandidat untuk dihapus jika sudah dipastikan tidak dibutuhkan.
- Password DB yang sempat ter-commit ke git history (lihat poin 4) sebaiknya dirotasi ulang untuk keamanan jangka panjang.
