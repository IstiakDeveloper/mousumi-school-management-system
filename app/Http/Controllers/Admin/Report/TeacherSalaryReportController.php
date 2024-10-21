<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\TeacherSalary;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeacherSalaryReportController extends Controller
{
    public function index(Request $request)
    {
        // Get the current year if no year is selected
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        // Default year and month to current if not provided
        $selectedYear = $request->input('year', $currentYear);
        $selectedMonth = $request->input('month', $currentMonth);

        // Get all teachers
        $teachers = Teacher::all();

        // Get start and end dates for the selected month
        $startOfMonth = Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endOfMonth = Carbon::create($selectedYear, $selectedMonth, 1)->endOfMonth();

        // Get all teacher salaries before the selected month (across all years)
        $previousMonthSalaries = TeacherSalary::where('month', '<', $selectedMonth)
            ->where('year', $selectedYear)
            ->sum('amount'); // Removed the 'whereYear' condition to consider all previous months.

        // Get salaries for the current selected month
        $currentMonthSalaries = TeacherSalary::with('teacher')
            ->where('year', $selectedYear)
            ->where('month', $selectedMonth)
            ->get();

        // Calculate cumulative totals by adding previous total and each subsequent salary
        $runningTotal = $previousMonthSalaries;

        $currentMonthSalaries = $currentMonthSalaries->map(function ($salary) use (&$runningTotal) {
            // Add the current salary amount to the running total
            $runningTotal += $salary->amount;
            // Add cumulative total to the salary
            $salary->cumulative_total = $runningTotal;
            return $salary;
        });

        // Return the data to the Inertia view
        return Inertia::render('Admin/Report/TeacherSalaryReport', [
            'previousMonthSalaries' => $previousMonthSalaries,
            'currentMonthSalaries' => $currentMonthSalaries,
            'teachers' => $teachers,
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
