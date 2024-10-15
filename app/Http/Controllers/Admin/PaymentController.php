<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Payment;
use App\Models\BankBalance;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    /**
     * Display all students and their payment status based on the selected year and month.
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

        // Retrieve all students with their payment status
        $students = Student::with('schoolClass')->get()->map(function ($student) use ($year, $month) {
            $payment = Payment::where('student_id', $student->id)
                              ->where('year', $year)
                              ->where('month', $month)
                              ->first();

            $student->payment_status = $payment ? $payment->status : 'not_paid';
            $student->payment_details = $payment; // Add payment details for students who have paid
            return $student;
        });

        // Return the students and payment status data to the Vue component via Inertia
        return Inertia::render('Admin/Payments/Index', [
            'students' => $students,
            'year' => $year,
            'month' => $month,
        ]);
    }


    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'year' => 'required|integer',
            'month' => 'required|integer',
            'payment_method' => 'required|string|in:bank,cash',
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048', // Add file validation rules
        ]);

        // Store the uploaded payment proof
        $paymentProofPath = $request->file('payment_proof')->store('receipts', 'public');

        // Create or update the payment record
      $payment = Payment::updateOrCreate(
            [
                'student_id' => $request->input('student_id'),
                'year' => $request->input('year'),
                'month' => $request->input('month'),
            ],
            [
                'payment_method' => $request->input('payment_method'),
                'receipt' => $paymentProofPath,
                'status' => 'paid', // Set payment status as paid
                'amount' => 400,
            ]
        );


        $bankBalance = BankBalance::first(); // Assuming only one record exists, adjust as needed
        if ($bankBalance) {
            $bankBalance->addIncome(400); // Add the payment amount to the bank balance
        } else {
            // Optional: Create a new bank balance record if it doesn't exist
            BankBalance::create(['balance' => 400]); // Set initial balance if no record exists
        }

        // Redirect back with a success message
        return redirect()->route('payments.index')->with('success', 'Payment processed successfully!');
    }


    /**
     * Get the payment status of a student for the selected year and month.
     *
     * @param int $studentId
     * @param int $year
     * @param int $month
     * @return string
     */
    private function getPaymentStatus($studentId, $year, $month)
    {
        // Check if a payment record exists for the student using year and month fields
        $payment = Payment::where('student_id', $studentId)
            ->where('year', $year)
            ->where('month', $month)
            ->first();

        return $payment ? 'Paid' : 'Not Paid';
    }

    /**
     * Mark a student as paid for the selected year and month.
     *
     * @param Request $request
     * @param int $studentId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsPaid(Request $request, $studentId)
    {
        // Validate the year and month input
        $request->validate([
            'year' => 'required|integer|min:1900|max:' . now()->year,
            'month' => 'required|integer|min:1|max:12',
        ]);

        $year = $request->input('year');
        $month = $request->input('month');

        // Create a payment record or update existing
        Payment::updateOrCreate(
            ['student_id' => $studentId, 'year' => $year, 'month' => $month],
            ['receipt' => $request->file('payment_proof')->store('receipts'), 'status' => 'paid']
        );

        // Return a success response
        return redirect()->route('payments.index')->with('success', 'Payment processed successfully!');
    }
}
