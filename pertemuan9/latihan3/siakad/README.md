# Latihan 2a — SIAKAD (Sistem Informasi Akademik)

Aplikasi administrasi akademik berbasis **CodeIgniter 4.7**, PHP >= 8.2, dan
pola **Model–View–Controller**. Kebutuhan mengacu pada
[`overview_sistem_informasi_akademik_codeigniter.md`](../overview_sistem_informasi_akademik_codeigniter.md).

[Laporan lengkap: kode penting, model data, dan placeholder screenshot](../README.md#latihan-2a--siakad). Lokasi fisik aplikasi tetap `latihan3/siakad/`, sedangkan penomoran laporan menggunakan **Latihan 2a**.

## Fitur

- Login/logout administrator dengan password hash dan session.
- Dashboard berisi total mahasiswa dan lima mahasiswa terbaru.
- Daftar, tambah, edit, dan hapus mahasiswa.
- Pencarian NIM/nama/program studi dan pagination 10 data per halaman.
- Validasi di model, NIM unik, pesan Indonesia, dan pemulihan input form.
- Filter autentikasi, CSRF berbasis session, serta output yang di-escape.
- Layout Bootstrap 5 dengan tabel yang dapat digulir pada layar kecil.

Modul dosen, mata kuliah, jadwal, nilai, dan role tambahan merupakan pengembangan
lanjutan dalam overview. Akun pada versi ini semuanya administrator.

## Persiapan

Aktifkan ekstensi PHP `intl`, `mbstring`, serta `mysqli`/`mysqlnd` untuk MySQL.
Pengujian memerlukan `sqlite3` dan menggunakan SQLite in-memory terpisah.
Bootstrap 5.3.3 dimuat melalui jsDelivr sehingga styling Bootstrap memerlukan internet.

Jalankan seluruh perintah berikut dari folder `siakad/`:

```console
composer install
```

Salin `.env.example` menjadi `.env` jika file lokal tersebut belum ada.
Sesuaikan `app.baseURL`, koneksi database, dan `admin.password` (minimal 12
karakter). Seeder membaca kredensial dari environment dan menyimpan hash.

### MySQL/MariaDB

Buat database melalui phpMyAdmin atau SQL:

```sql
CREATE DATABASE siakad CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Isi `.env` sesuai akun database lokal:

```ini
database.default.hostname = localhost
database.default.database = siakad
database.default.username = root
database.default.password = ''
database.default.DBDriver = MySQLi
database.default.port = 3306
```

### SQLite untuk pengembangan lokal

Jika MySQL belum berjalan, gunakan pengaturan berikut pada `.env`:

```ini
database.default.DBDriver = SQLite3
database.default.database = siakad.sqlite
database.default.DBPrefix = ''
```

CodeIgniter menyimpan nama file SQLite tanpa direktori tersebut di
`writable/siakad.sqlite`. File ini diabaikan Git. Pada sesi implementasi,
konfigurasi lokal memakai SQLite karena layanan MySQL belum tersedia.

### Migration dan seeder

```console
php spark migrate
php spark db:seed DatabaseSeeder
php spark serve --port 8082
```

Buka **http://localhost:8082/login** dan gunakan `admin.username` serta
`admin.password` yang ditentukan di `.env`.

`DatabaseSeeder` membuat admin dan lima mahasiswa fiktif. Seeder dapat dijalankan
kembali tanpa menggandakan data atau menimpa akun yang sudah ada. Mengubah
`admin.password` di `.env` tidak mengubah password akun yang sudah dibuat.

Untuk PHP XAMPP yang DLL `intl` dan `sqlite3`-nya tersedia tetapi belum aktif,
perintah alternatif berikut memuat ekstensi hanya untuk proses terkait:

```console
php -d extension=intl -d extension=sqlite3 spark migrate
php -d extension=intl -d extension=sqlite3 spark db:seed DatabaseSeeder
php -d extension=intl -d extension=sqlite3 -S localhost:8082 -t public vendor/codeigniter4/framework/system/rewrite.php
```

Web server diarahkan ke `public/`, bukan root aplikasi.

## Pola MVC dan gaya kode

| Bagian | Lokasi | Tanggung jawab |
|---|---|---|
| Controller | `app/Controllers/Auth.php` | Login, regenerasi session, logout |
| Controller | `app/Controllers/Dashboard.php` | Menyiapkan ringkasan dashboard |
| Controller | `app/Controllers/Mahasiswa.php` | Request CRUD, model, view, redirect, 404 |
| Model | `app/Models/UserModel.php` | Akses akun, allowed fields, timestamp |
| Model | `app/Models/MahasiswaModel.php` | Validasi mahasiswa dan query pencarian |
| View | `app/Views/` | Layout, partial, form, tabel, pagination |
| Filter | `app/Filters/AuthFilter.php` | Pembatasan akses berdasarkan session |
| Database | `app/Database/` | Migration dan data awal |

Kode PHP baru mengadaptasi style guide CI2 yang diminta: tab, kurung Allman,
variabel/method aplikasi `snake_case`, `TRUE`/`FALSE`/`NULL`, satu kelas per file,
single-quoted string, dan tanpa closing tag pada file PHP murni. Namespace,
nama kelas PSR-4 (`MahasiswaModel`, `AuthFilter`), properti model, dan signature
method override tetap mengikuti API CI4. Teks antarmuka di `app/Language/id/`.

Route didefinisikan eksplisit di `app/Config/Routes.php`; auto-routing dimatikan.
Seluruh route `/admin` dan logout memakai filter `auth`. Tambah/update/hapus/logout
menggunakan POST dengan token CSRF. ID pembaruan selalu berasal dari route.

## Pengujian

```console
composer test
php spark routes
php spark filter:check get /admin
```

Alternatif untuk ekstensi yang belum aktif di `php.ini`:

```console
php -d extension=intl -d extension=sqlite3 vendor/phpunit/phpunit/phpunit --no-coverage
```

`tests/feature/SiakadTest.php` menguji akses tanpa login, login/logout, CSRF,
CRUD, validasi, NIM duplikat, ID tidak ditemukan, pencarian, pagination,
escaping output, dan seeder idempoten. Database test tidak menggunakan file
SQLite aplikasi atau database MySQL pengembangan.

### Hasil eksekusi untuk laporan

Pada **3 November 2026**, perintah PHPUnit alternatif di atas berhasil dijalankan menggunakan PHP 8.2.12 dan PHPUnit 10.5.66:

```text
OK (21 tests, 92 assertions)
```

Hasil tersebut adalah pengujian otomatis. Pengambilan screenshot dan pemeriksaan visual seluruh halaman belum dicatat selesai.

## Screenshot

Placeholder login, dashboard, daftar/pencarian mahasiswa, form tambah/edit, validasi, hasil perubahan, dan tampilan mobile tersedia di [bagian hasil tampilan laporan](../README.md#hasil-tampilan-siakad).

Simpan gambar di `../../docs/screenshots/latihan2-siakad/`, lalu aktifkan Markdown gambar pada placeholder terkait. Lihat [panduan screenshot](../../docs/screenshots/README.md) untuk daftar nama file.

## Referensi resmi

- [Style guide pilihan pengguna (CI2)](https://www.codeigniter.com/userguide2/general/styleguide.html)
- [MVC CodeIgniter 4](https://codeigniter.com/user_guide/concepts/mvc.html)
- [Model dan validasi](https://codeigniter.com/user_guide/models/model.html)
- [Controller Filters](https://codeigniter.com/user_guide/incoming/filters.html)
- [Database Migrations](https://codeigniter.com/user_guide/dbmgmt/migration.html)
- [Session](https://codeigniter.com/user_guide/libraries/sessions.html)
- [CSRF](https://codeigniter.com/user_guide/libraries/security.html)

[Kembali ke daftar latihan](../../README.md)
