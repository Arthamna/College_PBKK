using System.Collections.Generic;

namespace DataMahasiswa;

class MahasiswaService
{
    private readonly List<Mahasiswa> daftarMahasiswa = new();

    public void Tambah(Mahasiswa mahasiswa)
    {
        daftarMahasiswa.Add(mahasiswa);
    }

    public List<Mahasiswa> AmbilSemua()
    {
        return daftarMahasiswa;
    }

    public Mahasiswa? Cari(string nim)
    {
        foreach (Mahasiswa mahasiswa in daftarMahasiswa)
        {
            if (mahasiswa.NIM.Equals(nim, StringComparison.OrdinalIgnoreCase))
                return mahasiswa;
        }

        return null;
    }

    public bool Hapus(string nim)
    {
        Mahasiswa? mahasiswa = Cari(nim);

        if (mahasiswa == null)
            return false;

        daftarMahasiswa.Remove(mahasiswa);
        return true;
    }
}