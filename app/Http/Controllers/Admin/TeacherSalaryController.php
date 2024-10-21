<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeacherSalary;
use App\Models\BankBalance;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeacherSalaryController extends Controller
{
    /**
     * Display all teachers and their salary payment status based on the selected year and month.
     *
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        // Set the current year and month as default values
        $currentYear = now()->year;
        $currentMonth = now()->month;

        // Get the year and month from the request, or default to the current year and month
        $year = $request->input('year', $currentYear);
        $month = $request->input('month', $currentMonth);

        // Retrieve all teachers with their salary payment status and related user information
        $teachers = Teacher::with('user') // Eager load the related user data
            ->get()
            ->map(function ($teacher) use ($year, $month) {
                // Retrieve salary details for the given year and month
                $salary = TeacherSalary::where('teacher_id', $teacher->id)
                                       ->where('year', $year)
                                       ->where('month', $month)
                                       ->first();

                // Add salary status and salary details
                $teacher->salary_status = $salary ? $salary->status : 'not_paid';
                $teacher->salary_details = $salary; // Add salary details for teachers who have been paid

                // Add user information to the teacher data
                $teacher->user = $teacher->user; // This will include the related user data

                return $teacher;
            });

        // Return the teachers and salary status data to the Vue component via Inertia
        return Inertia::render('Admin/TeacherSalaries/Index', [
            'teachers' => $teachers,
            'year' => $year,
            'month' => $month,
        ]);
    }


    /**
     * Store a teacher's salary payment record.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'year' => 'required|integer',
            'month' => 'required|integer',
            'payment_method' => 'required|string',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $teacher = Teacher::findOrFail($request->input('teacher_id'));
        $salaryAmount = $teacher->salary_amount;

        // Store the uploaded receipt (if any)
        $receiptPath = $request->file('receipt') ? $request->file('receipt')->store('receipts', 'public') : null;

        // Create or update the teacher salary record
        $teacherSalary = TeacherSalary::updateOrCreate(
            [
                'teacher_id' => $request->input('teacher_id'),
                'year' => $request->input('year'),
                'month' => $request->input('month'),
            ],
            [
                'payment_method' => $request->input('payment_method'),
                'receipt' => $receiptPath,
                'status' => 'paid', // Set salary status as paid
                'amount' => $salaryAmount,
            ]
        );

;        // Update the bank balance (if payment method is 'bank')

        $bankBalance = BankBalance::first(); // Assuming only one record exists, adjust as needed
        if ($bankBalance) {
            $bankBalance->deductExpense($salaryAmount); // Add the payment amount to the bank balance
        } else {
            // Optional: Create a new bank balance record if it doesn't exist
            BankBalance::create(['balance' => $salaryAmount]); // Set initial balance if no record exists
        }


        // Redirect back with a success message
        return redirect()->back()->with('success', 'Teacher salary recorded successfully!');
    }


    public function payAllTeachers(Request $request)
{
    // Validate incoming request data
    $request->validate([
        'year' => 'required|integer',
        'month' => 'required|integer',
        'payment_method' => 'required|string',
        'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    // Get all teachers
    $teachers = Teacher::all();

    // Store the uploaded receipt (if any)
    $receiptPath = $request->file('receipt') ? $request->file('receipt')->store('receipts', 'public') : null;

    // Loop through each teacher and create/update the salary record
    foreach ($teachers as $teacher) {
        // Check if the salary for the given year and month is already paid
        $teacherSalary = TeacherSalary::where('teacher_id', $teacher->id)
                                      ->where('year', $request->input('year'))
                                      ->where('month', $request->input('month'))
                                      ->first();

        // Skip if the salary is already paid for this teacher
        if ($teacherSalary && $teacherSalary->status === 'paid') {
            continue; // Skip this iteration and move to the next teacher
        }

        // Get the teacher's salary amount
        $salaryAmount = $teacher->salary_amount;

        // Update or create the salary record for the teacher
        TeacherSalary::updateOrCreate(
            [
                'teacher_id' => $teacher->id,
                'year' => $request->input('year'),
                'month' => $request->input('month'),
            ],
            [
                'payment_method' => $request->input('payment_method'),
                'receipt' => $receiptPath,
                'status' => 'paid',
                'amount' => $salaryAmount,
            ]
        );

        // Optionally, update the bank balance
        $bankBalance = BankBalance::first(); // Assuming only one record exists, adjust as needed
        if ($bankBalance) {
            $bankBalance->deductExpense($salaryAmount); // Deduct the payment amount from the bank balance
        } else {
            // Optional: Create a new bank balance record if it doesn't exist
            BankBalance::create(['balance' => $salaryAmount]); // Set initial balance if no record exists
        }
    }

        // Return a success response
        return redirect()->route('salaries.index')->with('success', 'All teacher salaries recorded successfully!');
    }


    /**
     * Get the salary payment status of a teacher for the selected year and month.
     *
     * @param int $teacherId
     * @param int $year
     * @param int $month
     * @return string
     */
    private function getSalaryStatus($teacherId, $year, $month)
    {
        // Check if a salary record exists for the teacher using year and month fields
        $salary = TeacherSalary::where('teacher_id', $teacherId)
            ->where('year', $year)
            ->where('month', $month)
            ->first();

        return $salary ? 'Paid' : 'Not Paid';
    }

    /**
     * Mark a teacher as paid for the selected year and month.
     *
     * @param Request $request
     * @param int $teacherId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsPaid(Request $request, $teacherId)
    {
        // Validate the year and month input
        $request->validate([
            'year' => 'required|integer|min:1900|max:' . now()->year,
            'month' => 'required|integer|min:1|max:12',
        ]);

        $year = $request->input('year');
        $month = $request->input('month');

        // Create a salary record or update existing
        TeacherSalary::updateOrCreate(
            ['teacher_id' => $teacherId, 'year' => $year, 'month' => $month],
            ['receipt' => $request->file('receipt') ? $request->file('receipt')->store('receipts', 'public') : null, 'status' => 'paid']
        );

        // Return a success response
        return redirect()->route('teacher_salaries.index')->with('success', 'Teacher salary marked as paid successfully!');
    }
}
