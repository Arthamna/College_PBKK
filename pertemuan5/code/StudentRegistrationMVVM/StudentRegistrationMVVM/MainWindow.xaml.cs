using System.Windows;
using StudentRegistrationMVVM.ViewModels;

namespace StudentRegistrationMVVM;

public partial class MainWindow : Window
{
    public MainWindow()
    {
        InitializeComponent();
        DataContext = new MahasiswaViewModel();
    }
}
