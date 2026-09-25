using System.Windows;
using System.Windows.Controls;

namespace StudentRegistration
{
    public partial class MainWindow : Window
    {
        public MainWindow()
        {
            InitializeComponent();
        }

        private void BtnSimpan_Click(object sender, RoutedEventArgs e)
        {
            string nim = txtNim.Text;
            string nama = txtNama.Text;

            if (string.IsNullOrWhiteSpace(nim))
            {
                MessageBox.Show("NIM harus diisi!");
                txtNim.Focus();
                return;
            }

            if (string.IsNullOrWhiteSpace(nama))
            {
                MessageBox.Show("Nama harus diisi!");
                txtNama.Focus();
                return;
            }

            if (cmbProdi.SelectedItem == null)
            {
                MessageBox.Show("Pilih program studi!");
                return;
            }

            if (rbLaki.IsChecked != true && rbPerempuan.IsChecked != true)
            {
                MessageBox.Show("Pilih jenis kelamin!");
                return;
            }

            string prodi = ((ComboBoxItem)cmbProdi.SelectedItem).Content.ToString();
            string jenisKelamin = rbLaki.IsChecked == true ? "Laki-laki" : "Perempuan";
            string data = $"{nim} | {nama} | {prodi} | {jenisKelamin}";

            lstMahasiswa.Items.Add(data);

            MessageBox.Show(
                "Data mahasiswa berhasil disimpan!",
                "Informasi",
                MessageBoxButton.OK,
                MessageBoxImage.Information);
        }

        private void BtnReset_Click(object sender, RoutedEventArgs e)
        {
            txtNim.Clear();
            txtNama.Clear();
            cmbProdi.SelectedIndex = -1;
            rbLaki.IsChecked = false;
            rbPerempuan.IsChecked = false;
            txtNim.Focus();
        }

        private void BtnHapus_Click(object sender, RoutedEventArgs e)
        {
            if (lstMahasiswa.SelectedItem == null)
            {
                MessageBox.Show("Pilih data mahasiswa yang akan dihapus!");
                return;
            }

            lstMahasiswa.Items.Remove(lstMahasiswa.SelectedItem);
        }
    }
}
