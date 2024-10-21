<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentFeeReportController extends Controller
{
    public function index(Request $request)
    {
        // Get the current year if no year is selected
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        // Default year and month to current if not provided
        $selectedYear = $request->input('year', $currentYear);
        $selectedMonth = $request->input('month', $currentMonth);

        // Fetch all students
        $students = Student::all();

        // Get start and end dates for the selected month
        $startOfMonth = Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endOfMonth = Carbon::create($selectedYear, $selectedMonth, 1)->endOfMonth();

        // Get all payments before the selected month (across all years)
        $previousMonthPayments = Payment::where('created_at', '<', $startOfMonth)
            ->sum('amount');

        // Get payments for the current selected month
        $currentMonthPayments = Payment::with('student')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->get();

        // Calculate cumulative totals
        $runningTotal = $previousMonthPayments;
        $currentMonthPayments = $currentMonthPayments->map(function ($payment) use (&$runningTotal) {
            $runningTotal += $payment->amount;
            $payment->cumulative_total = $runningTotal;
            return $payment;
        });

        // Return the data to the Inertia view
        return Inertia::render('Admin/Report/StudentFeeReport', [
            'previousMonthPayments' => $previousMonthPayments,
            'currentMonthPayments' => $currentMonthPayments,
            'students' => $students,
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
