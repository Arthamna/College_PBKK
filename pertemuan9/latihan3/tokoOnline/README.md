# Latihan 2b — Toko Online

Toko Online adalah aplikasi katalog dan keranjang belanja berbasis **CodeIgniter 4.7.6**, PHP 8.2+, dan pola MVC. Antarmuka menggunakan merek **RuangBelanja**, Bootstrap 5.3.3, dan CSS lokal.

[Laporan lengkap: kode penting, model data, dan placeholder screenshot](../README.md#latihan-2b--toko-online).

## Modul yang sudah diimplementasikan

- [x] Katalog publik dengan pagination 12 produk per halaman.
- [x] Detail produk berisi deskripsi, harga, stok, dan form jumlah belanja.
- [x] Keranjang session: tambah, ubah jumlah, hapus item, dan kosongkan.
- [x] Validasi jumlah terhadap stok serta perhitungan subtotal/total rupiah bulat.
- [x] Pembaruan harga dan penyesuaian stok keranjang berdasarkan database.
- [x] Login/logout administrator dengan hash password dan regenerasi session.
- [x] CRUD produk dengan pagination 10 data per halaman.
- [x] Upload JPEG/PNG/WebP maksimal 2 MB dan penggantian gambar produk.
- [x] Filter akses admin, CSRF, serta output yang di-escape.
- [x] Migration dan seeder akun admin serta 13 produk contoh.
- [ ] Melengkapi screenshot pada laporan.

Aplikasi saat ini berfokus pada katalog, pengelolaan produk, dan keranjang. Modul checkout, pembayaran, pengiriman, dan riwayat pesanan belum tersedia.

## Struktur implementasi

| Bagian | File / direktori | Tanggung jawab |
|---|---|---|
| Routing | `app/Config/Routes.php` | Route eksplisit untuk publik, cart, dan admin |
| Controller | `app/Controllers/CatalogController.php` | Katalog dan detail produk |
| Controller | `app/Controllers/CartController.php` | Request perubahan keranjang |
| Library | `app/Libraries/Cart.php` | Session, validasi stok, rekonsiliasi, subtotal/total |
| Controller admin | `app/Controllers/Admin/` | Autentikasi dan CRUD/upload produk |
| Model | `app/Models/` | Data akun, validasi, dan penyimpanan produk |
| Filter | `app/Filters/AdminAuthFilter.php` | Membatasi akses panel admin |
| View | `app/Views/` | Layout toko/admin, partial, katalog, dan form |
| Database | `app/Database/` | Migration `admins`/`products` dan seeder |

Login dilakukan melalui `/admin/login`. Setelah login, `/admin` mengarah ke `/admin/products`. Tidak ada dashboard statistik terpisah.

Gambar tersimpan di `writable/uploads/products/` dan ditampilkan melalui `/products/{id}/image`. Produk tanpa gambar menggunakan placeholder bawaan. Keranjang hanya menyimpan ID dan jumlah; harga dibaca ulang dari database. Logout admin mempertahankan keranjang pengunjung.

## Instalasi dan konfigurasi

### 1. Persiapan

Siapkan PHP 8.2+, Composer, ekstensi `intl` dan `mbstring`, serta driver database. Aktifkan `mysqli`/`mysqlnd` untuk MySQL atau `sqlite3` untuk SQLite. Bootstrap dimuat melalui CDN sehingga styling memerlukan internet.

Jalankan perintah dari folder `latihan3/tokoOnline/`:

```console
composer install
```

Salin `.env.example` menjadi `.env` jika belum ada. Gunakan konfigurasi berikut sebagai contoh dan sesuaikan akun database lokal:

```ini
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8083/'
app.indexPage = ''

database.default.hostname = localhost
database.default.database = toko_online
database.default.username = root
database.default.password = ''
database.default.DBDriver = MySQLi
database.default.port = 3306

shop.adminName = 'Admin Toko'
shop.adminEmail = 'admin@example.com'
shop.adminPassword = 'GANTI_DENGAN_PASSWORD_LOKAL'
```

`shop.adminPassword` harus diganti dengan password lokal minimal 12 karakter sebelum menjalankan seeder. `.env` tidak disertakan dalam Git.

### 2. Database

Untuk MySQL/MariaDB, buat database terlebih dahulu:

```sql
CREATE DATABASE toko_online CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Alternatif SQLite: ganti pengaturan driver/database pada `.env` dengan:

```ini
database.default.DBDriver = SQLite3
database.default.database = toko_online.sqlite
database.default.DBPrefix = ''
```

Nama file SQLite tanpa direktori disimpan oleh CodeIgniter di `writable/`. Kemudian jalankan:

```console
php spark migrate
php spark db:seed DatabaseSeeder
php spark serve --port 8083
```

Buka **http://localhost:8083/**. Login admin memakai email dan password yang diatur sebelum seeder dijalankan. Seeder melewati akun yang sudah ada dan tidak mengganti password akun tersimpan.

Untuk PHP XAMPP dengan DLL `intl`/`sqlite3` tersedia tetapi belum aktif, jalankan alternatif berikut jika menggunakan SQLite:

```console
php -d extension=intl -d extension=sqlite3 spark migrate
php -d extension=intl -d extension=sqlite3 spark db:seed DatabaseSeeder
php -d extension=intl -d extension=sqlite3 -S localhost:8083 -t public vendor/codeigniter4/framework/system/rewrite.php
```

Document root selalu diarahkan ke `public/`.

## Pengujian

Jalankan dari folder aplikasi:

```console
composer test
```

Perintah yang dieksekusi untuk laporan ini:

```console
php -d extension=intl -d extension=sqlite3 vendor/phpunit/phpunit/phpunit --no-coverage
```

**Hasil pada 3 November 2026: OK — 19 tests, 75 assertions**, dengan PHP 8.2.12 dan PHPUnit 10.5.66. Pengujian menggunakan database testing SQLite in-memory.

`tests/feature/ShopTest.php` memeriksa katalog, pagination, detail/404, escaping, akses admin, login/logout, CRUD, validasi produk, dan operasi keranjang termasuk harga terbaru dan penyesuaian stok. Hasil ini tidak menyatakan verifikasi visual atau pengujian upload melalui browser telah selesai.

## Screenshot

Placeholder katalog, detail produk, keranjang, validasi stok, login admin, CRUD produk, upload gambar, dan tampilan mobile tersedia di [bagian hasil tampilan laporan](../README.md#hasil-tampilan-toko-online).

Simpan gambar di `../../docs/screenshots/latihan2-toko-online/`, kemudian aktifkan Markdown gambar pada placeholder yang bersangkutan. Ikuti [panduan screenshot](../../docs/screenshots/README.md) untuk nama file.

## Referensi

- [CodeIgniter Models](https://codeigniter.com/user_guide/models/model.html)
- [Session](https://codeigniter.com/user_guide/libraries/sessions.html)
- [File Uploads](https://codeigniter.com/user_guide/libraries/uploaded_files.html)
- [Controller Filters](https://codeigniter.com/user_guide/incoming/filters.html)
- [Testing](https://codeigniter.com/user_guide/testing/overview.html)
- [Overview tutorial](../overview_toko_online_codeigniter.md)

[Kembali ke daftar latihan](../../README.md)
