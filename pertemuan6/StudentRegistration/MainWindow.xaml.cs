using System.Windows;
using StudentRegistration.Repositories;

namespace StudentRegistration;

public partial class MainWindow : Window
{
    private readonly StudentRepository _studentRepository;

    public MainWindow()
    {
        InitializeComponent();

        _studentRepository = new StudentRepository();

        LoadStudents();
    }

    private void LoadStudents()
    {
        try
        {
            var students = _studentRepository.GetAll();
            var totalStudents = _studentRepository.GetCount();

            StudentDataGrid.ItemsSource = students;
            TotalStudentsTextBlock.Text = $"Total Mahasiswa: {totalStudents}";
        }
        catch (Exception ex)
        {
            MessageBox.Show(
                ex.Message,
                "Database Error",
                MessageBoxButton.OK,
                MessageBoxImage.Error);
        }
    }

    private void RefreshButton_Click(object sender, RoutedEventArgs e)
    {
        LoadStudents();
    }
}
