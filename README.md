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
| `csrf.php` | Helper token CSRF per-session (`csrf_field()`, `csrf_verify()`) dipakai form login & input klien. |
| `login.php`, `action-login.php`, `action-logout.php` | Autentikasi sederhana berbasis session (kredensial wajib dari env var / `secrets.php`, tidak ada fallback default). |
| `phpqrcode/` | Library pihak ketiga untuk generate QR code (di-vendor langsung ke repo ini). |
| `sql.sql` | Skema tabel `tamu`. |

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
11. **Audit keamanan menyeluruh & pembersihan endpoint lama** — ditemukan sejumlah file peninggalan "buku tamu" versi awal yang masih live di server dan merusak model keamanan aplikasi meski tidak dipakai alur aktif, karena PHP tetap menjalankan file apa pun yang ada di document root terlepas ada link ke sana atau tidak:
    - **SQL injection**: `simpan.php.ori` membangun query dengan concatenation string langsung.
    - **Bypass kode rahasia**: `detail_id.php` & `ds-ori/detail_id.php` membuka detail klien lewat `id` yang berurutan/predictable (bisa dienumerasi untuk memanen semua kode `msg` rahasia); `ds-ori/index.php` bahkan punya field `name="msg"` yang membiarkan pengguna menentukan sendiri kode rahasianya.
    - **Broken access control**: `cari.php`, `cari1.php`, `cari-ori.php`, `search-engine.php`, `tampil.php` menampilkan/mencari seluruh data tamu tanpa login sama sekali (bahkan dump semua data kalau parameter kosong).
    - **Stored XSS**: `tampil.php` menampilkan data tanpa `htmlspecialchars()`, sedangkan datanya bisa diisi lewat endpoint insert yang juga tanpa login.
    - **Endpoint insert tanpa autentikasi**: `simpan.php`, `simpan.php.ori`, `simpantest.php`, `ds-ori/simpan.php`.

    Semua file di atas (plus `form.html`, `tabel.html`, `validjs.js`, `search.php`, `search-app.php`, `index.php.ori`, `session/test-app.php`, dan folder `ds-ori/`) sudah **dihapus** karena tidak dipakai alur aktif (`login.php` → `index.php` → `generate_link.php` → `detail.php`) dan tidak ada nilai untuk dipertahankan.
12. **Rotasi password DB** — password DB yang sempat ter-commit ke git history (lihat poin 4) sudah dirotasi ulang lewat Plesk dan `secrets.php` di server sudah diperbarui. Koneksi sudah dikonfirmasi normal dengan password baru.
13. **CSRF protection** — `csrf.php` menambahkan token CSRF per-session (`csrf_field()` untuk menyisipkan token di form, `csrf_verify()` untuk memvalidasi). Dipasang di form login (`login.php` → `action-login.php`) dan form input klien (`index.php` → `generate_link.php`); submit tanpa token yang valid akan ditolak dan diarahkan kembali ke form.
14. **Kolom `timestamp`** — sudah dikonfirmasi ada di database production, meski tidak tercantum di `sql.sql` (skema di repo ini sedikit tertinggal dari skema production).
15. **Bug penutupan tag PHP dini di `csrf.php`** — komentar penjelasan cara pakai di baris 6 mengandung literal `?>` (contoh potongan kode `<?php echo csrf_field(); ?>`). Komentar `//` di PHP berakhir di baris baru **atau** di `?>`, mana pun lebih dulu, sehingga `?>` tersebut menutup mode PHP untuk seluruh sisa file — definisi `csrf_token()`, `csrf_field()`, dan `csrf_verify()` tidak pernah diparse sebagai kode, melainkan ikut ter-output sebagai teks mentah, sehingga CSRF protection gagal total di production meski file di server sudah sesuai commit terbaru. Diperbaiki dengan menghilangkan literal `?>` dari komentar.
16. **Password DB tidak sinkron setelah rotasi** — setelah rotasi password di poin 12, sempat terjadi lagi `Access denied for user 't42590_ds'@'localhost'` karena `secrets.php` di server (satu folder di atas document root) tidak/kadaluarsa sinkron dengan password user MySQL yang aktif di Plesk. Diperbaiki dengan menyamakan kembali password di **Plesk → Databases** dan isi `secrets.php`.

17. **Persiapan repo public** — audit sebelum repo ini dijadikan public menemukan dua masalah kredensial:
    - `action-login.php` (dan salinan lama di `session/action-login.php`, sudah dihapus) punya fallback ke kredensial default (`user`/`user123!?` dan `rizky`/`passwordlogin`) yang ditulis dalam bentuk hash + disebut plaintext-nya di komentar. Kalau `secrets.php` gagal termuat di server (pernah terjadi, lihat poin #16), aplikasi tetap bisa login pakai kredensial default yang sekarang jadi publik. Diperbaiki jadi **fail-closed**: kalau `DS_LOGIN_USERNAME`/`DS_LOGIN_PASSWORD_HASH` tidak diset, semua percobaan login ditolak.
    - Password DB asli sempat ter-commit plaintext di history git (poin #4, commit `f656de6`). Meski sudah dirotasi (poin #12), history git tetap menyimpan password lama secara plaintext. History di-rewrite untuk menghapusnya sebelum repo dijadikan public.
    - Folder `session/` (salinan alur login/app lama, tidak dipakai flow aktif) dihapus karena tetap bisa diakses langsung dan memakai kredensial default yang lebih lemah.

### Yang Masih Perlu Diperhatikan

- Skema `sql.sql` di repo belum mencantumkan kolom `timestamp` yang sudah ada di production — sebaiknya disinkronkan supaya `sql.sql` bisa dipakai untuk setup ulang database dari nol.
- Belum ada rate limiting pada percobaan login maupun pengisian form `generate_link.php`; pertimbangkan menambahkannya kalau aplikasi ini dipakai untuk data yang lebih sensitif atau traffic publik yang lebih tinggi.
