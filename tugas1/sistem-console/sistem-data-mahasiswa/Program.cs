namespace DataMahasiswa;

class Program
{
    static MahasiswaService mahasiswaService = new();

    static void Main(string[] args)
    {
        int pilihan;

        do
        {
            TampilkanMenu();
            Console.Write("Pilihan: ");

            if (!int.TryParse(Console.ReadLine(), out pilihan))
                pilihan = 0;

            Console.WriteLine();

            switch (pilihan)
            {
                case 1:
                    TambahMahasiswa();
                    break;
                case 2:
                    TampilkanMahasiswa();
                    break;
                case 3:
                    CariMahasiswa();
                    break;
                case 4:
                    HapusMahasiswa();
                    break;
                case 5:
                    Console.WriteLine("Terima kasih telah menggunakan program.");
                    break;
                default:
                    Console.WriteLine("Pilihan tidak tersedia!");
                    break;
            }

            if (pilihan != 5)
            {
                Console.WriteLine();
                Console.WriteLine("Tekan ENTER untuk melanjutkan...");
                Console.ReadLine();
            }

        } while (pilihan != 5);
    }

    static void TampilkanMenu()
    {
        Console.Clear();
        Console.WriteLine("========================================");
        Console.WriteLine(" SISTEM DATA MAHASISWA");
        Console.WriteLine("========================================");
        Console.WriteLine("1. Tambah Mahasiswa");
        Console.WriteLine("2. Tampilkan Mahasiswa");
        Console.WriteLine("3. Cari Mahasiswa");
        Console.WriteLine("4. Hapus Mahasiswa");
        Console.WriteLine("5. Keluar");
        Console.WriteLine("========================================");
    }

    static void TambahMahasiswa()
    {
        Console.Clear();
        Console.WriteLine("========================================");
        Console.WriteLine(" TAMBAH MAHASISWA");
        Console.WriteLine("========================================");

        Console.Write("NIM : ");
        string nim = Console.ReadLine() ?? "";

        Console.Write("Nama : ");
        string nama = Console.ReadLine() ?? "";

        Console.Write("Program Studi : ");
        string prodi = Console.ReadLine() ?? "";

        double ipk;

        while (true)
        {
            Console.Write("IPK : ");

            if (double.TryParse(Console.ReadLine(), out ipk))
            {
                if (ipk >= 0 && ipk <= 4)
                    break;
            }

            Console.WriteLine("IPK harus berupa angka 0 - 4.");
        }

        Mahasiswa mahasiswa = new(nim, nama, prodi, ipk);

        mahasiswaService.Tambah(mahasiswa);

        Console.WriteLine();
        Console.WriteLine("Data mahasiswa berhasil ditambahkan.");
    }

    static void TampilkanMahasiswa()
    {
        Console.Clear();
        Console.WriteLine("==========================================================");
        Console.WriteLine(" DAFTAR MAHASISWA");
        Console.WriteLine("==========================================================");

        List<Mahasiswa> daftarMahasiswa = mahasiswaService.AmbilSemua();

        if (daftarMahasiswa.Count == 0)
        {
            Console.WriteLine("Belum ada data mahasiswa.");
            return;
        }

        Console.WriteLine(
            "{0,-12} {1,-20} {2,-20} {3,5}",
            "NIM", "Nama", "Prodi", "IPK"
        );

        Console.WriteLine(
            "----------------------------------------------------------"
        );

        foreach (Mahasiswa mahasiswa in daftarMahasiswa)
        {
            Console.WriteLine(
                "{0,-12} {1,-20} {2,-20} {3,5:F2}",
                mahasiswa.NIM,
                mahasiswa.Nama,
                mahasiswa.Prodi,
                mahasiswa.IPK
            );
        }

        Console.WriteLine(
            "=========================================================="
        );
    }

    static void CariMahasiswa()
    {
        Console.Clear();
        Console.WriteLine("========================================");
        Console.WriteLine(" CARI MAHASISWA");
        Console.WriteLine("========================================");

        Console.Write("Masukkan NIM: ");
        string nim = Console.ReadLine() ?? "";

        Mahasiswa? mahasiswa = mahasiswaService.Cari(nim);

        Console.WriteLine();

        if (mahasiswa != null)
        {
            Console.WriteLine("Data ditemukan!");
            Console.WriteLine("NIM : " + mahasiswa.NIM);
            Console.WriteLine("Nama : " + mahasiswa.Nama);
            Console.WriteLine("Prodi : " + mahasiswa.Prodi);
            Console.WriteLine("IPK : " + mahasiswa.IPK.ToString("F2"));
        }
        else
        {
            Console.WriteLine(
                "Mahasiswa dengan NIM tersebut tidak ditemukan."
            );
        }
    }

    static void HapusMahasiswa()
    {
        Console.Clear();
        Console.WriteLine("========================================");
        Console.WriteLine(" HAPUS MAHASISWA");
        Console.WriteLine("========================================");

        Console.Write("Masukkan NIM: ");
        string nim = Console.ReadLine() ?? "";

        bool berhasil = mahasiswaService.Hapus(nim);

        Console.WriteLine();

        if (berhasil)
            Console.WriteLine("Data mahasiswa berhasil dihapus.");
        else
            Console.WriteLine("Data mahasiswa tidak ditemukan.");
    }
}