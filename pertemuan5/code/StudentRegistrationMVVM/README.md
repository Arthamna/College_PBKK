# Student Registration MVVM

Aplikasi desktop WPF sederhana untuk mengelola data mahasiswa dengan pola MVVM dan database MySQL.

## Menyiapkan database

1. Jalankan MySQL.
2. Eksekusi `StudentRegistrationMVVM/schema.sql` melalui MySQL Workbench atau klien MySQL lainnya.
3. Secara default aplikasi memakai koneksi berikut:

```text
Server=localhost;Port=3306;Database=student_db;User ID=root;Password=;
```

Jika kredensial berbeda, atur environment variable sebelum menjalankan aplikasi:

```powershell
$env:STUDENT_DB_CONNECTION_STRING = "Server=localhost;Port=3306;Database=student_db;User ID=root;Password=password_kamu;"
```

Kredensial tidak perlu disimpan dalam source code.

## Menjalankan aplikasi

```powershell
dotnet restore
dotnet run --project StudentRegistrationMVVM/StudentRegistrationMVVM.csproj
```

Jika MySQL belum aktif atau schema belum dibuat, aplikasi tetap terbuka dan menampilkan kesalahan koneksi pada bagian bawah form.
