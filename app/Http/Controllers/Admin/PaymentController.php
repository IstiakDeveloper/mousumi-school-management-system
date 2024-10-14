<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Payment;
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
        // Validate the year and month input
        $request->validate([
            'year' => 'required|integer|min:1900|max:' . now()->year,
            'month' => 'required|integer|min:1|max:12',
        ]);

        // Get the year and month from the request
        $year = $request->input('year');
        $month = $request->input('month');

        // Retrieve all students
        $students = Student::all();

        // Check the payment status for each student
        foreach ($students as $student) {
            $student->payment_status = $this->getPaymentStatus($student->id, $year, $month);
        }

        // Return the students and payment status data to the Vue component via Inertia
        return Inertia::render('Admin/Payments/Index', [
            'students' => $students,
            'year' => $year,
            'month' => $month,
        ]);
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

        // Check if payment already exists
        $payment = Payment::where('student_id', $studentId)
            ->where('year', $year)
            ->where('month', $month)
            ->first();

        if (!$payment) {
            // Create a new payment record
            Payment::create([
                'student_id' => $studentId,
                'year' => $year,
                'month' => $month,
                'amount' => 100, // Example payment amount
                'status' => 'paid', // Set the initial status to paid
            ]);

            return redirect()->back()->with('success', 'Payment marked as completed.');
        }

        return redirect()->back()->with('error', 'Payment already exists for this period.');
    }
}
