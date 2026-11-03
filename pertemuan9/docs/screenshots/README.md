# Panduan mengisi screenshot laporan

README aplikasi menyediakan blok **Placeholder** dan contoh Markdown gambar di dalam komentar HTML. Gambar belum disertakan; isi sendiri menggunakan screenshot aplikasi yang berjalan.

## Cara menambahkan gambar

1. Jalankan aplikasi sesuai README masing-masing.
2. Ambil screenshot halaman menggunakan data demo, tanpa identitas pribadi penulis atau NRP.
3. Buat subfolder di bawah `docs/screenshots/` sesuai tabel berikut, lalu simpan gambar dengan nama yang ditentukan.
4. Pada laporan, hapus blok placeholder terkait dan lepaskan `<!--` serta `-->` dari baris Markdown gambar.
5. Periksa preview Markdown untuk memastikan gambar tampil. Jika memakai nama/format file berbeda, sesuaikan path pada laporan.

Contoh, dari `latihan3/README.md`:

```markdown
![Login SIAKAD](../docs/screenshots/latihan2-siakad/01-login.png)

*Gambar S1. Halaman login administrator SIAKAD.*
```

Gunakan ukuran desktop yang konsisten, misalnya **1440 × 900**, dan **390 × 844** untuk mobile. Tunggu styling dan gambar selesai dimuat sebelum mengambil screenshot.

## Latihan 1 — Inventory Management

Folder: `docs/screenshots/latihan1/`. Placeholder ada di [README Inventory Management](../../latihan1/README.md#5-hasil-tampilan).

| Nama file | Halaman |
|---|---|
| `01-halaman-awal.png` | Halaman awal yang benar-benar tampil; kode saat ini masih welcome CodeIgniter |

## Latihan 2a — SIAKAD

Folder: `docs/screenshots/latihan2-siakad/`. Placeholder ada di [laporan SIAKAD](../../latihan3/README.md#hasil-tampilan-siakad).

| Placeholder | Nama file | Isi gambar |
|---|---|---|
| S1 | `01-login.png` | Login administrator |
| S2 | `02-dashboard.png` | Total mahasiswa dan daftar terbaru |
| S3 | `03-daftar-mahasiswa.png` | Daftar mahasiswa |
| S4 | `04-pencarian.png` | Hasil pencarian |
| S5 | `05-tambah-mahasiswa.png` | Form tambah mahasiswa |
| S6 | `06-validasi.png` | Pesan validasi form |
| S7 | `07-edit-mahasiswa.png` | Form edit mahasiswa |
| S8 | `08-hasil-perubahan.png` | Pesan sukses setelah data disimpan |
| S9 | `09-mobile.png` | Daftar mahasiswa pada mobile |

## Latihan 2b — Toko Online

Folder: `docs/screenshots/latihan2-toko-online/`. Placeholder ada di [laporan Toko Online](../../latihan3/README.md#hasil-tampilan-toko-online).

| Placeholder | Nama file | Isi gambar |
|---|---|---|
| T1 | `01-katalog.png` | Katalog publik |
| T2 | `02-detail-produk.png` | Detail, harga, dan stok |
| T3 | `03-keranjang.png` | Keranjang berisi produk dan total |
| T4 | `04-perubahan-jumlah.png` | Hasil pembaruan jumlah |
| T5 | `05-validasi-stok.png` | Pesan jumlah melebihi stok |
| T6 | `06-login-admin.png` | Login admin |
| T7 | `07-daftar-produk-admin.png` | Daftar produk admin |
| T8 | `08-tambah-produk.png` | Form tambah produk |
| T9 | `09-edit-produk.png` | Form edit dengan gambar yang sudah diunggah |
| T10 | `10-mobile.png` | Katalog pada mobile |

[Kembali ke daftar latihan](../../README.md)
