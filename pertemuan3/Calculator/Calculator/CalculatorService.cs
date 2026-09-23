using System;

namespace Calculator
{
    public class CalculatorService
    {
        public double Calculate(double firstNumber, double secondNumber, string operation)
        {
            return operation switch
            {
                "+" => firstNumber + secondNumber,
                "−" => firstNumber - secondNumber,
                "×" => firstNumber * secondNumber,
                "÷" => secondNumber == 0
                    ? throw new DivideByZeroException("Cannot divide by zero.")
                    : firstNumber / secondNumber,
                _ => 0
            };
        }

        public double Percentage(double number)
        {
            return number / 100;
        }

        public double ToggleSign(double number)
        {
            return -number;
        }

        public double Square(double number)
        {
            return number * number;
        }

        public double SquareRoot(double number)
        {
            if (number < 0)
                throw new ArgumentException("Cannot calculate square root of a negative number.");

            return Math.Sqrt(number);
        }

        public double Sin(double number)
        {
            return Math.Sin(number * Math.PI / 180);
        }

        public double Cos(double number)
        {
            return Math.Cos(number * Math.PI / 180);
        }

        public double Tan(double number)
        {
            return Math.Tan(number * Math.PI / 180);
        }
    }
}