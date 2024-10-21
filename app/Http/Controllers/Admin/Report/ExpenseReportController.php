<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExpenseReportController extends Controller
{
    public function index(Request $request)
    {
        // Get the current year if no year is selected
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        // Default year and month to current if not provided
        $selectedYear = $request->input('year', $currentYear);
        $selectedMonth = $request->input('month', $currentMonth);

        // Fetch all expense categories
        $expenseCategories = ExpenseCategory::all();

        // Get start and end dates for the selected month
        $startOfMonth = Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endOfMonth = Carbon::create($selectedYear, $selectedMonth, 1)->endOfMonth();

        // Get all expenses before the selected month (across all years)
        $previousMonthExpenses = Expense::where('date', '<', $startOfMonth)
            ->sum('amount');  // Removed the 'whereYear' condition to consider all previous years.

        // Get expenses for the current selected month
        $currentMonthExpenses = Expense::with('category')->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get();

        // Calculate cumulative totals by adding previous total and each subsequent expense
        $runningTotal = $previousMonthExpenses;

        $currentMonthExpenses = $currentMonthExpenses->map(function ($expense) use (&$runningTotal) {
            // Add the current expense amount to the running total
            $runningTotal += $expense->amount;
            // Add cumulative total to the expense
            $expense->cumulative_total = $runningTotal;
            return $expense;
        });

        // Return the data to the Inertia view
        return Inertia::render('Admin/Report/ExpenseReport', [
            'previousMonthExpenses' => $previousMonthExpenses,
            'currentMonthExpenses' => $currentMonthExpenses,
            'expenseCategories' => $expenseCategories,
            'selectedYear' => $selectedYear,
            'selectedMonth' => $selectedMonth,
            'years' => $this->getAvailableYears(),
            'months' => $this->getAvailableMonths(),
        ]);
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
