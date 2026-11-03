# Latihan 1 — Inventory Management

Inventory Management merupakan proyek latihan aplikasi pengelolaan persediaan menggunakan **CodeIgniter 4** dan pola **Model–View–Controller (MVC)**. Dokumentasi ini mencatat kondisi kode yang tersedia, konfigurasi awal, serta tempat untuk menambahkan screenshot hasil aplikasi.

## Daftar isi

- [1. Daftar pekerjaan](#1-daftar-pekerjaan)
- [2. Kode penting dan penjelasannya](#2-kode-penting-dan-penjelasannya)
- [3. Instalasi dan konfigurasi](#3-instalasi-dan-konfigurasi)
- [4. Model data](#4-model-data)
- [5. Hasil tampilan](#5-hasil-tampilan)
- [6. Referensi](#6-referensi)

## 1. Daftar pekerjaan

| Pekerjaan | Status | Bukti implementasi |
|---|---|---|
| Menyiapkan struktur aplikasi CodeIgniter | Tersedia | `composer.json`, `app/`, `public/`, `spark` |
| Mendefinisikan route halaman awal | Tersedia | `app/Config/Routes.php` |
| Menampilkan halaman welcome melalui controller dan view | Tersedia di kode | `app/Controllers/Home.php`, `app/Views/welcome_message.php` |
| Modul bisnis inventory | Belum tersedia | Belum ada controller, model, atau migration bisnis inventory |
| Screenshot aplikasi | Menunggu gambar | Placeholder pada bagian hasil tampilan |

Belum ada implementasi pengelolaan persediaan pada kode yang diperiksa. Daftar pekerjaan selesai di atas terbatas pada struktur awal aplikasi; belum dilakukan verifikasi visual halaman atau pengujian fitur bisnis inventory.

## 2. Kode penting dan penjelasannya

### Routing

File: [`app/Config/Routes.php`](app/Config/Routes.php).

```php
$routes->get('/', 'Home::index');
```

Request `GET /` diteruskan ke method `index()` pada controller `Home`.

### Controller dan view

File: [`app/Controllers/Home.php`](app/Controllers/Home.php).

```php
public function index(): string
{
    return view('welcome_message');
}
```

Controller mengembalikan view [`app/Views/welcome_message.php`](app/Views/welcome_message.php). Alur halaman awal adalah **request → router → controller → view → response HTML**. Halaman ini belum memanggil model karena belum mengakses data bisnis.

## 3. Instalasi dan konfigurasi

### Kebutuhan

- PHP **8.2 atau lebih baru** sesuai constraint `^8.2` pada `composer.json`.
- CodeIgniter **4** sesuai constraint `^4.7` pada `composer.json`.
- Composer serta ekstensi PHP `intl` dan `mbstring`.

Jalankan perintah dari folder `latihan1/`:

```console
composer install
```

Salin file `env` menjadi `.env`, lalu sesuaikan:

```ini
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8081/'
app.indexPage = ''
```

Jalankan server pengembangan:

```console
php spark serve --port 8081
```

Buka **http://localhost:8081/**. Untuk PHP XAMPP dengan ekstensi `intl` tersedia tetapi belum aktif, ekstensi dapat dimuat khusus untuk proses server:

```console
php -d extension=intl -S localhost:8081 -t public vendor/codeigniter4/framework/system/rewrite.php
```

Document root diarahkan ke `public/`. Halaman welcome belum memerlukan database; instruksi migration dan seeder bisnis akan mengikuti implementasinya jika tersedia.

## 4. Model data

Belum ada model, migration, atau seeder bisnis di `app/Models/` dan `app/Database/`. Karena itu, skema tabel dan ERD Inventory Management belum dapat diturunkan dari kode saat ini.

## 5. Hasil tampilan

> **Placeholder screenshot — Halaman awal Inventory Management.**
> Jalankan aplikasi dan tambahkan screenshot halaman yang benar-benar tampil. Pada kondisi kode saat ini, halaman awal masih berupa welcome CodeIgniter.

<!-- Setelah gambar tersedia, ganti blok placeholder di atas dengan baris berikut:
![Halaman awal Inventory Management](../docs/screenshots/latihan1/01-halaman-awal.png)
-->

*Gambar 1. Halaman awal aplikasi pada proyek Inventory Management.*

Panduan menambahkan gambar: [Screenshot dokumentasi](../docs/screenshots/README.md).

## 6. Referensi

- [Instalasi CodeIgniter 4](https://codeigniter.com/user_guide/installation/index.html)
- [Routing](https://codeigniter.com/user_guide/incoming/routing.html)
- [Controllers](https://codeigniter.com/user_guide/incoming/controllers.html)
- [Views](https://codeigniter.com/user_guide/outgoing/views.html)

[Kembali ke daftar latihan](../README.md)
