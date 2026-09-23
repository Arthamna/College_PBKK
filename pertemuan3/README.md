# Calculator App

Calculator App adalah aplikasi kalkulator berbasis Windows Forms yang mendukung operasi dasar, operasi scientific, dan riwayat perhitungan.

## Pengujian Aplikasi

Pengujian dilakukan menggunakan beberapa skenario operasi dasar.

| No. | Pengujian | Input | Expected Result | Status |
| --- | --- | --- | --- | --- |
| 1 | Penjumlahan | `10 + 20 =` | `30` | Berhasil |
| 2 | Pengurangan | `30 − 12 =` | `18` | Berhasil |
| 3 | Perkalian | `6 × 7 =` | `42` | Berhasil |
| 4 | Pembagian | `100 ÷ 4 =` | `25` | Berhasil |
| 5 | Desimal | `2.5 × 4 =` | `10` | Berhasil |
| 6 | Pembagian dengan nol | `10 ÷ 0 =` | Pesan error | Berhasil |
| 7 | Clear | Menekan tombol `C` | Display menjadi `0` | Berhasil |

## Refleksi Mahasiswa

### 1. Apa fungsi `object sender` pada event handler?

`object sender` digunakan untuk mengetahui objek atau control yang memicu sebuah event. Pada aplikasi kalkulator, `sender` digunakan untuk mengetahui tombol yang ditekan.

```csharp
Button button = (Button)sender;
```

Dengan demikian, program dapat mengambil nilai `Text` dari tombol tersebut.

### 2. Mengapa semua tombol angka dapat memakai satu `NumberButton_Click`?

Semua tombol angka menggunakan event handler yang sama karena tombol yang ditekan dapat diketahui melalui `sender`. Cara ini membuat kode lebih ringkas dan menghindari event handler terpisah untuk setiap angka.

### 3. Apa perbedaan `firstNumber`, `secondNumber`, dan `result`?

- `firstNumber` menyimpan angka pertama yang dimasukkan pengguna.
- `secondNumber` menyimpan angka kedua setelah operator dipilih.
- `result` menyimpan hasil operasi antara kedua angka.

Contoh untuk operasi `10 + 20 = 30`:

```text
firstNumber  = 10
operation    = "+"
secondNumber = 20
result       = 30
```

### 4. Mengapa pembagian dengan nol perlu divalidasi?

Pembagian dengan nol merupakan operasi yang tidak valid. Jika pengguna memasukkan `10 ÷ 0`, Calculator App akan menampilkan pesan error dan tidak melanjutkan operasi.

### 5. Bagaimana `try-catch` membantu menjaga aplikasi tetap stabil?

`try-catch` menangani error yang terjadi saat program berjalan. Error ditangkap pada bagian `catch` dan ditampilkan kepada pengguna sehingga aplikasi tidak langsung berhenti atau mengalami crash.
