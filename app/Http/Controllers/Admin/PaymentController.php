<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Student;
use App\Models\Payment;
use App\Models\BankBalance;
use Carbon\Carbon;
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
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        $students = Student::with(['schoolClass', 'section'])
            ->select('students.*')
            ->leftJoin('payments', function($join) use ($year, $month) {
                $join->on('students.id', '=', 'payments.student_id')
                    ->where('payments.year', $year)
                    ->where('payments.month', $month);
            })
            ->addSelect([
                'payment_id' => Payment::select('id')
                    ->whereColumn('student_id', 'students.id')
                    ->where('year', $year)
                    ->where('month', $month)
                    ->limit(1),
                'payment_status' => Payment::select('status')
                    ->whereColumn('student_id', 'students.id')
                    ->where('year', $year)
                    ->where('month', $month)
                    ->limit(1),
                'payment_method' => Payment::select('payment_method')
                    ->whereColumn('student_id', 'students.id')
                    ->where('year', $year)
                    ->where('month', $month)
                    ->limit(1),
                'payment_date' => Payment::select('created_at')
                    ->whereColumn('student_id', 'students.id')
                    ->where('year', $year)
                    ->where('month', $month)
                    ->limit(1)
            ])
            ->get()
            ->map(function ($student) {
                $student->payment_details = [
                    'status' => $student->payment_status ?? 'not_paid',
                    'method' => $student->payment_method,
                    'date' => $student->payment_date ? Carbon::parse($student->payment_date)->format('Y-m-d H:i:s') : null
                ];
                return $student;
            });

        $stats = [
            'total_students' => $students->count(),
            'paid_count' => $students->where('payment_status', 'paid')->count(),
            'total_amount' => Payment::where('year', $year)->where('month', $month)->sum('amount'),
            'pending_amount' => ($students->count() * 400) - Payment::where('year', $year)->where('month', $month)->sum('amount')
        ];

        return Inertia::render('Admin/Payments/Index', [
            'students' => $students,
            'stats' => $stats,
            'year' => $year,
            'month' => $month,
            'filters' => $request->only(['search', 'status', 'class_id'])
        ]);
    }

    public function store(StorePaymentRequest $request)
    {
        try {
            $paymentProofPath = $request->file('payment_proof')->store('public/receipts');

            $student = Student::findOrFail($request->student_id);

            $payment = Payment::create([
                'student_id' => $student->id,
                'year' => $request->year,
                'month' => $request->month,
                'payment_method' => $request->payment_method,
                'receipt' => $paymentProofPath,
                'status' => 'paid',
                'amount' => $student->monthly_fee ?? 400,
                'paid_by' => auth()->id(),
                'notes' => $request->notes
            ]);

            if ($request->payment_method === 'bank') {
                BankBalance::first()?->addIncome($payment->amount);
            }

            return back()->with('success', 'Payment recorded successfully');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to process payment: ' . $e->getMessage());
        }
    }

    public function generateInvoice(Payment $payment)
    {
        $payment->load(['student.schoolClass', 'student.section']);

        return Inertia::render('Admin/Payments/Invoice', [
            'payment' => $payment,
            'school' => [
                'name' => config('app.school_name'),
                'address' => config('app.school_address'),
                'logo' => config('app.school_logo'),
                'phone' => config('app.school_phone')
            ]
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

        // Create a payment record or update existing
        Payment::updateOrCreate(
            ['student_id' => $studentId, 'year' => $year, 'month' => $month],
            ['receipt' => $request->file('payment_proof')->store('receipts'), 'status' => 'paid']
        );

        // Return a success response
        return redirect()->route('payments.index')->with('success', 'Payment processed successfully!');
    }
}
