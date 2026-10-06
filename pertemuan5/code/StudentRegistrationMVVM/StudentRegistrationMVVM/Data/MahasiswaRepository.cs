using MySqlConnector;
using StudentRegistrationMVVM.Models;

namespace StudentRegistrationMVVM.Data;

public class MahasiswaRepository
{
    private readonly string _connectionString;

    public MahasiswaRepository()
    {
        _connectionString = Environment.GetEnvironmentVariable("STUDENT_DB_CONNECTION_STRING")
            ?? "Server=localhost;Port=3306;Database=student_db;User ID=root;Password=;";
    }

    public List<Mahasiswa> GetAll(string keyword = "")
    {
        var mahasiswa = new List<Mahasiswa>();

        using var connection = new MySqlConnection(_connectionString);
        connection.Open();

        const string sql = """
            SELECT id, nim, nama, prodi, jenis_kelamin,
                   tanggal_lahir, alamat, no_telepon
            FROM mahasiswa
            WHERE nim LIKE @keyword
               OR nama LIKE @keyword
               OR prodi LIKE @keyword
            ORDER BY id DESC
            """;

        using var command = new MySqlCommand(sql, connection);
        command.Parameters.AddWithValue("@keyword", $"%{keyword}%");

        using var reader = command.ExecuteReader();
        while (reader.Read())
        {
            mahasiswa.Add(new Mahasiswa
            {
                Id = reader.GetInt32("id"),
                Nim = reader.GetString("nim"),
                Nama = reader.GetString("nama"),
                Prodi = reader.GetString("prodi"),
                JenisKelamin = reader.GetString("jenis_kelamin"),
                TanggalLahir = reader.GetDateTime("tanggal_lahir"),
                Alamat = reader.GetString("alamat"),
                NoTelepon = reader.GetString("no_telepon")
            });
        }

        return mahasiswa;
    }

    public void Insert(Mahasiswa mahasiswa)
    {
        using var connection = new MySqlConnection(_connectionString);
        connection.Open();

        const string sql = """
            INSERT INTO mahasiswa
                (nim, nama, prodi, jenis_kelamin, tanggal_lahir, alamat, no_telepon)
            VALUES
                (@nim, @nama, @prodi, @jenisKelamin, @tanggalLahir, @alamat, @noTelepon)
            """;

        using var command = new MySqlCommand(sql, connection);
        AddParameters(command, mahasiswa);
        command.ExecuteNonQuery();
    }

    public void Update(Mahasiswa mahasiswa)
    {
        using var connection = new MySqlConnection(_connectionString);
        connection.Open();

        const string sql = """
            UPDATE mahasiswa
            SET nim = @nim,
                nama = @nama,
                prodi = @prodi,
                jenis_kelamin = @jenisKelamin,
                tanggal_lahir = @tanggalLahir,
                alamat = @alamat,
                no_telepon = @noTelepon
            WHERE id = @id
            """;

        using var command = new MySqlCommand(sql, connection);
        AddParameters(command, mahasiswa);
        command.Parameters.AddWithValue("@id", mahasiswa.Id);
        command.ExecuteNonQuery();
    }

    public void Delete(int id)
    {
        using var connection = new MySqlConnection(_connectionString);
        connection.Open();

        const string sql = "DELETE FROM mahasiswa WHERE id = @id";
        using var command = new MySqlCommand(sql, connection);
        command.Parameters.AddWithValue("@id", id);
        command.ExecuteNonQuery();
    }

    public bool NimExists(string nim, int excludedId = 0)
    {
        using var connection = new MySqlConnection(_connectionString);
        connection.Open();

        const string sql = """
            SELECT COUNT(*)
            FROM mahasiswa
            WHERE nim = @nim AND id <> @excludedId
            """;

        using var command = new MySqlCommand(sql, connection);
        command.Parameters.AddWithValue("@nim", nim);
        command.Parameters.AddWithValue("@excludedId", excludedId);

        return Convert.ToInt32(command.ExecuteScalar()) > 0;
    }

    private static void AddParameters(MySqlCommand command, Mahasiswa mahasiswa)
    {
        command.Parameters.AddWithValue("@nim", mahasiswa.Nim);
        command.Parameters.AddWithValue("@nama", mahasiswa.Nama);
        command.Parameters.AddWithValue("@prodi", mahasiswa.Prodi);
        command.Parameters.AddWithValue("@jenisKelamin", mahasiswa.JenisKelamin);
        command.Parameters.AddWithValue("@tanggalLahir", mahasiswa.TanggalLahir!.Value.Date);
        command.Parameters.AddWithValue("@alamat", mahasiswa.Alamat);
        command.Parameters.AddWithValue("@noTelepon", mahasiswa.NoTelepon);
    }
}
