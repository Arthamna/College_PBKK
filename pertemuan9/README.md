# Dokumentasi Praktikum CodeIgniter

Dokumentasi pekerjaan aplikasi berbasis CodeIgniter 4 dengan pola **Model–View–Controller (MVC)**.

## Daftar latihan

| Latihan | Sistem | Folder aplikasi | Dokumentasi |
|---|---|---|---|
| 1 | Inventory Management | `latihan1/` | [Laporan Latihan 1](latihan1/README.md) |
| 2a | SIAKAD — Sistem Informasi Akademik | `latihan3/siakad/` | [Laporan Latihan 2](latihan3/README.md#latihan-2a--siakad) · [Panduan SIAKAD](latihan3/siakad/README.md) |
| 2b | Toko Online | `latihan3/tokoOnline/` | [Laporan Latihan 2](latihan3/README.md#latihan-2b--toko-online) · [Panduan Toko Online](latihan3/tokoOnline/README.md) |



## Ringkasan pekerjaan

Cara menjalankan setiap repo :

```
php spark serve --port {{PORT_NAME}}
```

### Latihan 1 — Inventory Management


- [x] Struktur awal aplikasi CodeIgniter dan konfigurasi dependency.
- [x] Route `/`, controller `Home`, dan view welcome.
- [x] Dokumentasi kondisi implementasi dan placeholder screenshot.

Kode saat ini masih berupa aplikasi awal. Modul bisnis Inventory Management belum tersedia; rincian ada pada [laporan Latihan 1](latihan1/README.md).

### Latihan 2a — SIAKAD

- [x] Login/logout administrator dengan session dan password hash.
- [x] Dashboard total mahasiswa dan lima mahasiswa terbaru.
- [x] CRUD mahasiswa.
- [x] Pencarian NIM, nama, atau program studi dan pagination.
- [x] Validasi form, NIM unik, dan pemulihan input saat gagal.
- [x] Pembatasan akses admin dan CSRF.
- [x] Migration, seeder, dan pengujian otomatis.

### Latihan 2b — Toko Online

- [x] Katalog, detail produk, dan pagination.
- [x] Login/logout administrator dan pembatasan akses.
- [x] CRUD produk dan upload gambar.
- [x] Keranjang session: tambah, ubah jumlah, hapus item, dan kosongkan.
- [x] Validasi stok, subtotal, dan total belanja.
- [x] Penyesuaian keranjang terhadap perubahan harga, stok, atau produk yang dihapus.
- [x] Migration, seeder, dan pengujian otomatis.
