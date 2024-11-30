<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Fund;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FundReportController extends Controller
{
    public function index(Request $request)
    {
        // Get the current year and month if not provided
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        // Default to current year and month if none is provided
        $selectedYear = $request->input('year', $currentYear);
        $selectedMonth = $request->input('month', $currentMonth);

        // Get start and end dates for the selected month
        $startOfMonth = Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endOfMonth = Carbon::create($selectedYear, $selectedMonth, 1)->endOfMonth();

        // Get previous month's total fund balance
        $previousMonthFunds = Fund::where('date', '<', $startOfMonth)->sum('amount');

        // Get the fund data for the selected month
        $currentMonthFunds = Fund::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get();

        // Calculate the cumulative total for the current month
        $runningTotal = $previousMonthFunds;

        $currentMonthFunds = $currentMonthFunds->map(function ($fund) use (&$runningTotal) {
            // Update running total by adding or subtracting the fund amount
            $runningTotal += $fund->type === 'in' ? $fund->amount : -$fund->amount;
            // Add the cumulative total to each fund entry
            $fund->cumulative_total = $runningTotal;
            return $fund;
        });

        // Return the data to Inertia
        return Inertia::render('Admin/Report/FundReport', [
            'previousMonthFunds' => $previousMonthFunds,
            'currentMonthFunds' => $currentMonthFunds,
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
