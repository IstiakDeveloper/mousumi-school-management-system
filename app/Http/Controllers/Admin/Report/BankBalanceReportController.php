<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\BankBalance;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\TeacherSalary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BankBalanceReportController extends Controller
{
    public function index(Request $request)
    {
        // Get the current year and month if none are selected
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        // Use selected year and month if provided
        $selectedYear = $request->input('year', $currentYear);
        $selectedMonth = $request->input('month', $currentMonth);

        // Get start and end dates for the selected month
        $startOfMonth = Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endOfMonth = Carbon::create($selectedYear, $selectedMonth, 1)->endOfMonth();

        // Get all balances before the selected month
        $previousBalance = BankBalance::where('created_at', '<', $startOfMonth)
            ->orderBy('created_at', 'desc')
            ->first();

        // Get total payments (cash in) for the current month
        $totalCashIn = Payment::where('status', 'paid')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        // Get total expenses (cash out) for the current month
        $totalCashOutExpenses = Expense::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        // Get total teacher salaries (cash out) for the current month
        $totalCashOutSalaries = TeacherSalary::where('status', 'paid')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        // Total cash out (expenses + teacher salaries)
        $totalCashOut = $totalCashOutExpenses + $totalCashOutSalaries;

        // Calculate the current month's bank balance (previous balance + cash in - cash out)
        $initialBalance = $previousBalance ? $previousBalance->balance : 0;
        $bankBalance = $initialBalance + $totalCashIn - $totalCashOut;

        // Store the bank balance for the current month (optional, you may need to decide on how often this is updated)
        BankBalance::updateOrCreate(
            ['created_at' => $endOfMonth],
            ['balance' => $bankBalance]
        );

        // Fetch all relevant records for the report (date, cash in, cash out, balance)
        $monthlyReport = $this->getMonthlyReport($startOfMonth, $endOfMonth, $initialBalance);

        // Render the data to the Inertia view
        return Inertia::render('Admin/Report/BankBalanceReport', [
            'initialBalance' => $initialBalance,
            'totalCashIn' => $totalCashIn,
            'totalCashOut' => $totalCashOut,
            'bankBalance' => $bankBalance,
            'monthlyReport' => $monthlyReport,
            'selectedYear' => $selectedYear,
            'selectedMonth' => $selectedMonth,
            'years' => $this->getAvailableYears(),
            'months' => $this->getAvailableMonths(),
        ]);
    }

    /**
     * Get detailed monthly report for cash in, cash out, and balance.
     */
    protected function getMonthlyReport($startOfMonth, $endOfMonth, $initialBalance)
    {
        // Get all relevant transactions in the selected date range
        $payments = Payment::where('status', 'paid')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->get();

        $expenses = Expense::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->get();

        $salaries = TeacherSalary::where('status', 'paid')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->get();

        // Collect all transactions with their dates and amounts
        $transactions = collect();

        foreach ($payments as $payment) {
            $transactions->push([
                'date' => $payment->created_at->format('Y-m-d'),
                'cash_in' => $payment->amount,
                'cash_out' => 0,
            ]);
        }

        foreach ($expenses as $expense) {
            $transactions->push([
                'date' => $expense->date,
                'cash_in' => 0,
                'cash_out' => $expense->amount,
            ]);
        }

        foreach ($salaries as $salary) {
            $transactions->push([
                'date' => Carbon::parse($salary->year . '-' . $salary->month . '-01')->format('Y-m-d'),
                'cash_in' => 0,
                'cash_out' => $salary->amount,
            ]);
        }

        // Sort transactions by date
        $transactions = $transactions->sortBy('date')->values();

        // Add cumulative bank balance to each transaction
        $runningBalance = $initialBalance;
        $transactions = $transactions->map(function ($transaction) use (&$runningBalance) {
            $runningBalance += $transaction['cash_in'] - $transaction['cash_out'];
            $transaction['bank_balance'] = $runningBalance;
            return $transaction;
        });

        return $transactions;
    }

    /**
     * Get available years for the report filter.
     */
    protected function getAvailableYears()
    {
        $currentYear = Carbon::now()->year;
        return range($currentYear - 5, $currentYear + 5);
    }

    /**
     * Get available months for the report filter.
     */
    protected function getAvailableMonths()
    {
        return collect([
            ['value' => 1, 'name' => 'January'],
            ['value' => 2, 'name' => 'February'],
            ['value' => 3, 'name' => 'March'],
            ['value' => 4, 'name' => 'April'],
            ['value' => 5, 'name' => 'May'],
            ['value' => 6, 'name' => 'June'],
            ['value' => 7, 'name' => 'July'],
            ['value' => 8, 'name' => 'August'],
            ['value' => 9, 'name' => 'September'],
            ['value' => 10, 'name' => 'October'],
            ['value' => 11, 'name' => 'November'],
            ['value' => 12, 'name' => 'December'],
        ]);
    }
}
