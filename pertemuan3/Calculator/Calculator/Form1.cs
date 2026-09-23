using System;
using System.Drawing;
using System.Windows.Forms;

namespace Calculator
{
    public partial class Form1 : Form
    {
        double firstNumber = 0;
        double secondNumber = 0;
        string operation = "";

        TextBox txtDisplay;
        TableLayoutPanel table;
        ListBox historyList;
        Panel historyPanel;

        CalculatorService calculator = new CalculatorService();

        public Form1()
        {
            InitializeComponent();
            CreateCalculator();
        }

        private void CreateCalculator()
        {
            Color backgroundColor = Color.FromArgb(250, 247, 244);
            Color textColor = Color.FromArgb(59, 59, 70);
            Color primaryColor = Color.FromArgb(56, 111, 213);
            Color operatorColor = Color.FromArgb(116, 82, 69);

            Text = "Calculator App";
            ClientSize = new Size(420, 700);
            StartPosition = FormStartPosition.CenterScreen;
            BackColor = backgroundColor;
            FormBorderStyle = FormBorderStyle.FixedSingle;
            MaximizeBox = false;

            Label lblTitle = new Label();
            lblTitle.Text = "CALCULATOR APP";
            lblTitle.Dock = DockStyle.Top;
            lblTitle.Height = 58;
            lblTitle.TextAlign = ContentAlignment.MiddleCenter;
            lblTitle.Font = new Font("Segoe UI", 18, FontStyle.Bold);
            lblTitle.ForeColor = textColor;
            lblTitle.BackColor = backgroundColor;

            txtDisplay = new TextBox();
            txtDisplay.Text = "0";
            txtDisplay.Dock = DockStyle.Fill;
            txtDisplay.Margin = new Padding(5, 5, 5, 8);
            txtDisplay.TextAlign = HorizontalAlignment.Right;
            txtDisplay.Font = new Font("Segoe UI", 28, FontStyle.Regular);
            txtDisplay.ForeColor = textColor;
            txtDisplay.BackColor = Color.White;
            txtDisplay.BorderStyle = BorderStyle.FixedSingle;
            txtDisplay.ReadOnly = true;

            table = new TableLayoutPanel();
            table.Dock = DockStyle.Fill;
            table.Padding = new Padding(18);
            table.ColumnCount = 4;
            table.RowCount = 7;
            table.BackColor = backgroundColor;

            for (int i = 0; i < 4; i++)
            {
                table.ColumnStyles.Add(
                    new ColumnStyle(SizeType.Percent, 25)
                );
            }

            table.RowStyles.Add(new RowStyle(SizeType.Absolute, 78));
            for (int i = 0; i < 4; i++)
                table.RowStyles.Add(new RowStyle(SizeType.Percent, 25));
            table.RowStyles.Add(new RowStyle(SizeType.Absolute, 112));
            table.RowStyles.Add(new RowStyle(SizeType.Absolute, 58));

            table.Controls.Add(txtDisplay, 0, 0);
            table.SetColumnSpan(txtDisplay, 3);
            AddButton(table, "⌫", BackspaceButton_Click, 3, 0);

            AddButton(table, "7", NumberButton_Click, 0, 1);
            AddButton(table, "8", NumberButton_Click, 1, 1);
            AddButton(table, "9", NumberButton_Click, 2, 1);
            AddButton(table, "÷", OperatorButton_Click, 3, 1);

            AddButton(table, "4", NumberButton_Click, 0, 2);
            AddButton(table, "5", NumberButton_Click, 1, 2);
            AddButton(table, "6", NumberButton_Click, 2, 2);
            AddButton(table, "×", OperatorButton_Click, 3, 2);

            AddButton(table, "1", NumberButton_Click, 0, 3);
            AddButton(table, "2", NumberButton_Click, 1, 3);
            AddButton(table, "3", NumberButton_Click, 2, 3);
            AddButton(table, "−", OperatorButton_Click, 3, 3);

            AddButton(table, "0", NumberButton_Click, 0, 4);
            AddButton(table, ".", btnDecimal_Click, 1, 4);
            AddButton(table, "=", btnEquals_Click, 2, 4);
            AddButton(table, "+", OperatorButton_Click, 3, 4);

            TableLayoutPanel scientificTable = new TableLayoutPanel();
            scientificTable.Dock = DockStyle.Fill;
            scientificTable.Margin = new Padding(0, 8, 0, 8);
            scientificTable.ColumnCount = 4;
            scientificTable.RowCount = 2;
            scientificTable.BackColor = backgroundColor;

            for (int i = 0; i < 4; i++)
                scientificTable.ColumnStyles.Add(new ColumnStyle(SizeType.Percent, 25));
            for (int i = 0; i < 2; i++)
                scientificTable.RowStyles.Add(new RowStyle(SizeType.Percent, 50));

            AddButton(scientificTable, "sin", ScientificButton_Click, 0, 0);
            AddButton(scientificTable, "cos", ScientificButton_Click, 1, 0);
            AddButton(scientificTable, "tan", ScientificButton_Click, 2, 0);
            AddButton(scientificTable, "√", ScientificButton_Click, 3, 0);
            AddButton(scientificTable, "x²", ScientificButton_Click, 0, 1);
            AddButton(scientificTable, "%", PercentageButton_Click, 1, 1);
            AddButton(scientificTable, "±", SignButton_Click, 2, 1);
            AddButton(scientificTable, "C", btnClear_Click, 3, 1);

            table.Controls.Add(scientificTable, 0, 5);
            table.SetColumnSpan(scientificTable, 4);

            Button historyButton = AddButton(
                table,
                "HISTORY",
                HistoryButton_Click,
                0,
                6
            );
            table.SetColumnSpan(historyButton, 4);

            historyPanel = new Panel();
            historyPanel.Dock = DockStyle.Fill;
            historyPanel.BackColor = backgroundColor;
            historyPanel.Visible = false;

            TableLayoutPanel historyLayout = new TableLayoutPanel();
            historyLayout.Dock = DockStyle.Fill;
            historyLayout.Padding = new Padding(20);
            historyLayout.ColumnCount = 1;
            historyLayout.RowCount = 4;
            historyLayout.BackColor = backgroundColor;
            historyLayout.ColumnStyles.Add(new ColumnStyle(SizeType.Percent, 100));
            historyLayout.RowStyles.Add(new RowStyle(SizeType.Absolute, 55));
            historyLayout.RowStyles.Add(new RowStyle(SizeType.Percent, 100));
            historyLayout.RowStyles.Add(new RowStyle(SizeType.Absolute, 58));
            historyLayout.RowStyles.Add(new RowStyle(SizeType.Absolute, 58));

            Label historyTitle = new Label();
            historyTitle.Text = "CALCULATION HISTORY";
            historyTitle.Dock = DockStyle.Fill;
            historyTitle.TextAlign = ContentAlignment.MiddleCenter;
            historyTitle.Font = new Font("Segoe UI", 15, FontStyle.Bold);
            historyTitle.ForeColor = textColor;

            historyList = new ListBox();
            historyList.Dock = DockStyle.Fill;
            historyList.Margin = new Padding(5, 5, 5, 12);
            historyList.BackColor = Color.White;
            historyList.ForeColor = textColor;
            historyList.Font = new Font("Segoe UI", 11);
            historyList.BorderStyle = BorderStyle.FixedSingle;

            Button clearHistoryButton = new Button();
            clearHistoryButton.Text = "CLEAR HISTORY";
            clearHistoryButton.Dock = DockStyle.Fill;
            clearHistoryButton.Margin = new Padding(5);
            clearHistoryButton.Font = new Font("Segoe UI", 11, FontStyle.Bold);
            clearHistoryButton.FlatStyle = FlatStyle.Flat;
            clearHistoryButton.FlatAppearance.BorderSize = 0;
            clearHistoryButton.BackColor = operatorColor;
            clearHistoryButton.ForeColor = Color.White;
            clearHistoryButton.Cursor = Cursors.Hand;
            clearHistoryButton.Click += ClearHistoryButton_Click;

            Button backButton = new Button();
            backButton.Text = "← BACK TO CALCULATOR";
            backButton.Dock = DockStyle.Fill;
            backButton.Margin = new Padding(5);
            backButton.Font = new Font("Segoe UI", 11, FontStyle.Bold);
            backButton.FlatStyle = FlatStyle.Flat;
            backButton.FlatAppearance.BorderSize = 0;
            backButton.BackColor = primaryColor;
            backButton.ForeColor = Color.White;
            backButton.Cursor = Cursors.Hand;
            backButton.Click += BackToCalculatorButton_Click;

            historyLayout.Controls.Add(historyTitle, 0, 0);
            historyLayout.Controls.Add(historyList, 0, 1);
            historyLayout.Controls.Add(backButton, 0, 2);
            historyLayout.Controls.Add(clearHistoryButton, 0, 3);
            historyPanel.Controls.Add(historyLayout);

            Panel contentPanel = new Panel();
            contentPanel.Dock = DockStyle.Fill;
            contentPanel.BackColor = backgroundColor;
            contentPanel.Controls.Add(historyPanel);
            contentPanel.Controls.Add(table);

            Controls.Add(contentPanel);
            Controls.Add(lblTitle);
        }

        private Button AddButton(
            TableLayoutPanel target,
            string text,
            EventHandler handler,
            int column,
            int row)
        {
            Button button = new Button();

            button.Text = text;
            button.Dock = DockStyle.Fill;
            button.Margin = new Padding(5);
            button.Font = new Font("Segoe UI", 13, FontStyle.Regular);

            button.FlatStyle = FlatStyle.Flat;
            button.FlatAppearance.BorderSize = 0;
            button.ForeColor = Color.FromArgb(59, 59, 70);
            button.BackColor = Color.FromArgb(220, 222, 229);
            button.Cursor = Cursors.Hand;

            if (text == "+" ||
                text == "−" ||
                text == "×" ||
                text == "÷")
            {
                button.BackColor = Color.FromArgb(116, 82, 69);
                button.ForeColor = Color.White;
            }

            if (text == "=" ||
                text == "⌫" ||
                text == "C" ||
                text == "HISTORY")
            {
                button.BackColor = Color.FromArgb(56, 111, 213);
                button.ForeColor = Color.White;
                button.Font = new Font("Segoe UI", 13, FontStyle.Bold);
            }

            if (text == "sin" ||
                text == "cos" ||
                text == "tan" ||
                text == "√" ||
                text == "x²" ||
                text == "%" ||
                text == "±")
            {
                button.BackColor = Color.FromArgb(232, 225, 219);
                button.ForeColor = Color.FromArgb(59, 59, 70);
                button.Font = new Font("Segoe UI", 11, FontStyle.Bold);
            }

            button.Click += handler;

            target.Controls.Add(button, column, row);
            return button;
        }

        private void NumberButton_Click(
            object sender,
            EventArgs e)
        {
            Button button = (Button)sender;

            if (txtDisplay.Text == "0")
                txtDisplay.Text = button.Text;
            else
                txtDisplay.Text += button.Text;
        }

        private void OperatorButton_Click(
            object sender,
            EventArgs e)
        {
            Button button = (Button)sender;

            firstNumber =
                double.Parse(txtDisplay.Text);

            operation = button.Text;
            txtDisplay.Clear();
        }

        private void btnEquals_Click(
            object sender,
            EventArgs e)
        {
            try
            {
                secondNumber =
                    double.Parse(txtDisplay.Text);

                double result =
                    calculator.Calculate(
                        firstNumber,
                        secondNumber,
                        operation
                    );

                string calculation =
                    $"{firstNumber} {operation} {secondNumber} = {result}";

                txtDisplay.Text =
                    result.ToString();

                historyList.Items.Add(calculation);
            }
            catch (Exception ex)
            {
                MessageBox.Show(
                    ex.Message,
                    "Error",
                    MessageBoxButtons.OK,
                    MessageBoxIcon.Error
                );
            }
        }

        private void btnClear_Click(
            object sender,
            EventArgs e)
        {
            firstNumber = 0;
            secondNumber = 0;
            operation = "";
            txtDisplay.Text = "0";
        }

        private void btnDecimal_Click(
            object sender,
            EventArgs e)
        {
            if (!txtDisplay.Text.Contains("."))
                txtDisplay.Text += ".";
        }

        private void SignButton_Click(
            object sender,
            EventArgs e)
        {
            try
            {
                double number =
                    double.Parse(txtDisplay.Text);

                txtDisplay.Text =
                    calculator.ToggleSign(number).ToString();
            }
            catch
            {
                txtDisplay.Text = "0";
            }
        }

        private void PercentageButton_Click(
            object sender,
            EventArgs e)
        {
            try
            {
                double number =
                    double.Parse(txtDisplay.Text);

                txtDisplay.Text =
                    calculator.Percentage(number).ToString();
            }
            catch
            {
                txtDisplay.Text = "0";
            }
        }

        private void BackspaceButton_Click(
            object sender,
            EventArgs e)
        {
            if (txtDisplay.Text.Length > 1)
            {
                txtDisplay.Text =
                    txtDisplay.Text.Substring(
                        0,
                        txtDisplay.Text.Length - 1
                    );
            }
            else
            {
                txtDisplay.Text = "0";
            }
        }

        private void ScientificButton_Click(
            object sender,
            EventArgs e)
        {
            try
            {
                Button button =
                    (Button)sender;

                double number =
                    double.Parse(txtDisplay.Text);

                double result = 0;

                switch (button.Text)
                {
                    case "sin":
                        result =
                            calculator.Sin(number);
                        break;

                    case "cos":
                        result =
                            calculator.Cos(number);
                        break;

                    case "tan":
                        result =
                            calculator.Tan(number);
                        break;

                    case "√":
                        result =
                            calculator.SquareRoot(number);
                        break;

                    case "x²":
                        result =
                            calculator.Square(number);
                        break;
                }

                string calculation =
                    $"{button.Text}({number}) = {result}";

                txtDisplay.Text =
                    result.ToString();

                historyList.Items.Add(
                    calculation
                );
            }
            catch (Exception ex)
            {
                MessageBox.Show(
                    ex.Message,
                    "Error",
                    MessageBoxButtons.OK,
                    MessageBoxIcon.Error
                );
            }
        }

        private void HistoryButton_Click(
            object sender,
            EventArgs e)
        {
            historyPanel.Visible =
                !historyPanel.Visible;

            if (historyPanel.Visible)
            {
                table.Visible = false;
                txtDisplay.Visible = false;
            }
            else
            {
                table.Visible = true;
                txtDisplay.Visible = true;
            }
        }

        private void BackToCalculatorButton_Click(
            object sender,
            EventArgs e)
        {
            historyPanel.Visible = false;
            table.Visible = true;
            txtDisplay.Visible = true;
        }

        private void ClearHistoryButton_Click(
            object sender,
            EventArgs e)
        {
            historyList.Items.Clear();
        }
    }
}
