using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Runtime.CompilerServices;
using System.Text.RegularExpressions;
using System.Windows.Input;
using StudentRegistrationMVVM.Data;
using StudentRegistrationMVVM.Models;

namespace StudentRegistrationMVVM.ViewModels;

public class MahasiswaViewModel : INotifyPropertyChanged
{
    private readonly MahasiswaRepository _repository = new();
    private Mahasiswa _formMahasiswa = CreateEmptyMahasiswa();
    private Mahasiswa? _selectedMahasiswa;
    private string _searchText = "";
    private string _statusMessage = "";

    public ObservableCollection<Mahasiswa> DaftarMahasiswa { get; } = [];

    public string[] DaftarProdi { get; } =
    [
        "Teknik Informatika",
        "Sistem Informasi",
        "Manajemen",
        "Akuntansi"
    ];

    public string[] DaftarJenisKelamin { get; } = ["Laki-laki", "Perempuan"];

    public Mahasiswa FormMahasiswa
    {
        get => _formMahasiswa;
        private set
        {
            _formMahasiswa = value;
            OnPropertyChanged();
        }
    }

    public Mahasiswa? SelectedMahasiswa
    {
        get => _selectedMahasiswa;
        set
        {
            _selectedMahasiswa = value;
            OnPropertyChanged();

            if (value is not null)
            {
                FormMahasiswa = CopyMahasiswa(value);
            }
        }
    }

    public string SearchText
    {
        get => _searchText;
        set
        {
            _searchText = value;
            OnPropertyChanged();
        }
    }

    public string StatusMessage
    {
        get => _statusMessage;
        private set
        {
            _statusMessage = value;
            OnPropertyChanged();
        }
    }

    public int JumlahMahasiswa => DaftarMahasiswa.Count;

    public ICommand SaveCommand { get; }
    public ICommand DeleteCommand { get; }
    public ICommand ResetCommand { get; }
    public ICommand SearchCommand { get; }

    public MahasiswaViewModel()
    {
        SaveCommand = new RelayCommand(Save);
        DeleteCommand = new RelayCommand(Delete);
        ResetCommand = new RelayCommand(ResetForm);
        SearchCommand = new RelayCommand(Search);

        LoadData();
    }

    private void LoadData(string keyword = "")
    {
        try
        {
            DaftarMahasiswa.Clear();
            foreach (var mahasiswa in _repository.GetAll(keyword))
            {
                DaftarMahasiswa.Add(mahasiswa);
            }

            OnPropertyChanged(nameof(JumlahMahasiswa));
            StatusMessage = "";
        }
        catch (Exception exception)
        {
            StatusMessage = $"Gagal membaca database: {exception.Message}";
        }
    }

    private void Save()
    {
        NormalizeForm();
        var validationMessage = ValidateForm();

        if (validationMessage is not null)
        {
            StatusMessage = validationMessage;
            return;
        }

        try
        {
            if (_repository.NimExists(FormMahasiswa.Nim, FormMahasiswa.Id))
            {
                StatusMessage = "NIM sudah terdaftar.";
                return;
            }

            var statusMessage = FormMahasiswa.Id == 0
                ? "Data mahasiswa berhasil disimpan."
                : "Data mahasiswa berhasil diperbarui.";

            if (FormMahasiswa.Id == 0)
            {
                _repository.Insert(FormMahasiswa);
            }
            else
            {
                _repository.Update(FormMahasiswa);
            }

            LoadData(SearchText.Trim());
            ResetForm(false);
            StatusMessage = statusMessage;
        }
        catch (Exception exception)
        {
            StatusMessage = $"Gagal menyimpan data: {exception.Message}";
        }
    }

    private void Delete()
    {
        if (SelectedMahasiswa is null)
        {
            StatusMessage = "Pilih mahasiswa yang akan dihapus.";
            return;
        }

        try
        {
            _repository.Delete(SelectedMahasiswa.Id);
            LoadData(SearchText.Trim());
            ResetForm(false);
            StatusMessage = "Data mahasiswa berhasil dihapus.";
        }
        catch (Exception exception)
        {
            StatusMessage = $"Gagal menghapus data: {exception.Message}";
        }
    }

    private void Search()
    {
        LoadData(SearchText.Trim());
    }

    private void ResetForm()
    {
        ResetForm(true);
    }

    private void ResetForm(bool clearMessage)
    {
        SelectedMahasiswa = null;
        FormMahasiswa = CreateEmptyMahasiswa();

        if (clearMessage)
        {
            StatusMessage = "";
        }
    }

    private string? ValidateForm()
    {
        if (!Regex.IsMatch(FormMahasiswa.Nim, @"^\d{8,12}$"))
        {
            return "NIM harus berupa 8 sampai 12 digit angka.";
        }

        if (FormMahasiswa.Nama.Length < 3 ||
            !Regex.IsMatch(FormMahasiswa.Nama, @"^[\p{L} .'-]+$"))
        {
            return "Nama minimal 3 karakter dan hanya berisi huruf.";
        }

        if (string.IsNullOrWhiteSpace(FormMahasiswa.Prodi))
        {
            return "Program studi harus dipilih.";
        }

        if (string.IsNullOrWhiteSpace(FormMahasiswa.JenisKelamin))
        {
            return "Jenis kelamin harus dipilih.";
        }

        if (FormMahasiswa.TanggalLahir is null)
        {
            return "Tanggal lahir harus diisi.";
        }

        if (FormMahasiswa.TanggalLahir.Value.Date > DateTime.Today.AddYears(-15))
        {
            return "Usia mahasiswa minimal 15 tahun.";
        }

        if (FormMahasiswa.Alamat.Length < 5)
        {
            return "Alamat minimal 5 karakter.";
        }

        if (!Regex.IsMatch(FormMahasiswa.NoTelepon, @"^(08|628)\d{8,11}$"))
        {
            return "Nomor telepon harus diawali 08 atau 628 dan berjumlah 10-14 digit.";
        }

        return null;
    }

    private void NormalizeForm()
    {
        FormMahasiswa.Nim = FormMahasiswa.Nim.Trim();
        FormMahasiswa.Nama = FormMahasiswa.Nama.Trim();
        FormMahasiswa.Alamat = FormMahasiswa.Alamat.Trim();
        FormMahasiswa.NoTelepon = FormMahasiswa.NoTelepon.Trim();
    }

    private static Mahasiswa CreateEmptyMahasiswa()
    {
        return new Mahasiswa
        {
            TanggalLahir = DateTime.Today.AddYears(-18)
        };
    }

    private static Mahasiswa CopyMahasiswa(Mahasiswa source)
    {
        return new Mahasiswa
        {
            Id = source.Id,
            Nim = source.Nim,
            Nama = source.Nama,
            Prodi = source.Prodi,
            JenisKelamin = source.JenisKelamin,
            TanggalLahir = source.TanggalLahir,
            Alamat = source.Alamat,
            NoTelepon = source.NoTelepon
        };
    }

    public event PropertyChangedEventHandler? PropertyChanged;

    private void OnPropertyChanged([CallerMemberName] string? propertyName = null)
    {
        PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(propertyName));
    }
}
