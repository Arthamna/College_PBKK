# ShopEase

Toko online pembelajaran dengan CodeIgniter 4.7, PHP 8.2+, MySQL/MariaDB InnoDB, dan Bootstrap 5. Modul Must PRD tersedia: autentikasi tiga role, CRUD katalog, gambar produk, keranjang, alamat, checkout, riwayat pesanan, pembayaran manual, dan verifikasi admin.

## Instalasi

Jalankan perintah dari folder ini. Aktifkan ekstensi PHP `intl`, `mbstring`, `mysqli`, `fileinfo`, `bcmath`, `dom`, dan `xml`. Bootstrap diambil dari CDN sehingga tampilan memerlukan internet.

1. Jalankan MySQL dan buat database kosong `db_shopease` dengan charset `utf8mb4`.
2. Jalankan `composer install`.
3. Salin `env` menjadi `.env` jika belum ada. Atur `CI_ENVIRONMENT = development`, `app.baseURL`, dan `database.default.*` sesuai MySQL Anda. Jangan commit `.env`.
4. Atur `shop.shippingCost = 10000.00`, `shop.bankInstructions` dengan rekening demo, serta `shop.seedPassword` dengan password lokal minimal delapan karakter.
5. Jalankan:

```sh
php spark migrate
php spark db:seed ShopSeeder
php spark serve
```

Buka `http://localhost:8080`. Seeder dapat dijalankan ulang tanpa menduplikasi data; tidak mengubah password akun yang sudah ada. Akun demo: `administrator@example.test`, `operator@example.test`, dan `customer@example.test`; password berasal dari `shop.seedPassword`. Registrasi publik selalu membuat customer.

PHP harus dapat menulis ke `writable` dan direktori `upload_tmp_dir` dari `php.ini`. Jika upload gagal pada XAMPP, buat direktori sementara yang dapat ditulis dan arahkan `upload_tmp_dir` ke sana sebelum menjalankan server. Batas PHP `upload_max_filesize` minimal 2 MB dan `post_max_size` lebih besar dari 2 MB.

## Alur penggunaan

- Administrator: login, kelola kategori/produk, harga, stok, status, dan gambar. Slug harus unik, huruf kecil/angka/tanda hubung.
- Customer: buka katalog, tambah keranjang, ubah kuantitas, checkout, tambah/pilih alamat, dan konfirmasi pesanan. Transfer sesuai instruksi lalu upload bukti JPEG/PNG maksimal 2 MB.
- Administrator: buka pesanan, lihat bukti, terima atau tolak dengan alasan. Penolakan memungkinkan customer mengunggah ulang. Pembayaran diterima memindahkan order ke `processing`.
- Administrator dapat membatalkan pesanan sebelum pembayaran diterima; stok dikembalikan satu kali. Pesanan tidak dihapus fisik.
- Operator hanya membaca pesanan pada tahap ini. Customer hanya membaca transaksi dan bukti miliknya.

## Aturan data

Checkout mengunci cart dan produk, menghitung harga dengan BCMath, menyimpan snapshot item/alamat, mengurangi stok, dan mengosongkan cart dalam satu transaksi. Token checkout unik mencegah submit ganda. Ongkir disalin dari konfigurasi ke order; perubahan tarif tidak mengubah order lama.

Status order: `pending_payment` ? `waiting_verification` ? `processing`. Penolakan kembali ke `pending_payment`; pembatalan ke `cancelled`. Status pembayaran terpisah: `unpaid`, `submitted`, `rejected`, `verified`. Pengiriman, pencarian/filter, pagination, dashboard statistik, dan laporan ditunda.

Gambar dan bukti tersimpan di `writable/uploads`, di luar document root. Bukti disajikan melalui endpoint berotorisasi. Produk dinonaktifkan; kategori yang masih mempunyai produk tidak dapat dihapus.

## Pengujian

```sh
php vendor/bin/phpunit --no-coverage
php spark routes
```

Tes integrasi otomatis dilewati apabila koneksi tes bukan MySQL dengan nama database berakhiran `_test`. **Gunakan database khusus** karena tes menghapus dan membuat ulang tabel ShopEase. Contoh PowerShell:

```powershell
${env:database.tests.hostname}='127.0.0.1'
${env:database.tests.port}='3306'
${env:database.tests.database}='shopease_test'
${env:database.tests.username}='YOUR_TEST_USER'
${env:database.tests.password}='YOUR_TEST_PASSWORD'
${env:database.tests.DBDriver}='MySQLi'
php vendor/bin/phpunit --no-coverage
```

Hasil sesi implementasi: 14 tes / 57 assertion lulus pada MariaDB 10.4 terisolasi; lint PHP dan registrasi route berhasil. Migration pada database kosong dan seed berulang berhasil. Smoke HTTP berhasil untuk login, checkout, upload PNG nyata, pengambilan bukti privat, dan verifikasi admin.

Belum diverifikasi: tampilan visual Chromium/Firefox pada desktop/mobile, upload berbahaya melalui browser, dan dua checkout yang benar-benar berjalan paralel. Tes stok dua pembeli saat ini berjalan berurutan; perlindungan konkurensi menggunakan row lock InnoDB.

## Deployment dan backup

Arahkan document root hanya ke `public`, gunakan environment production, konfigurasi database sendiri, HTTPS, dan izin tulis `writable`. Sertakan hanya source, dependensi produksi, dan aset; kecualikan `.env`, `ai-context`, database uji, log, cache, session, serta upload uji dari paket. Jangan menyalin seluruh working directory.

Backup database dengan `mysqldump --single-transaction -u USER -p db_shopease > backup.sql`, pulihkan menggunakan `mysql -u USER -p db_shopease < backup.sql` dari shell yang mendukung redirect input seperti cmd/bash. Cadangkan upload secara terpisah. Uji pemulihan pada database terpisah.

## Struktur dan referensi

`app/Controllers` menangani request dan otorisasi; `app/Models` memetakan tabel; `app/Services` menangani transaksi checkout/status; `app/Database` menyimpan migration/seed; `app/Views/shop` berisi UI; `tests/integration` menguji alur bisnis.

Referensi resmi: [MVC CI4](https://codeigniter.com/user_guide/concepts/mvc.html), [migration](https://codeigniter.com/user_guide/dbmgmt/migration.html), [transaksi](https://codeigniter.com/user_guide/database/transactions.html), [filters](https://codeigniter.com/user_guide/incoming/filters.html), [upload](https://codeigniter.com/user_guide/libraries/uploaded_files.html), [PHP password hashing](https://www.php.net/manual/en/function.password-hash.php). API juga diperiksa dari source framework 4.7.6 di `vendor/codeigniter4/framework/system`.
