<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeacherSalary;
use App\Models\BankBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $currentYear = now()->year;
        $currentMonth = now()->month;

        $year = $request->get('year', $currentYear);
        $month = $request->get('month', $currentMonth);

        $teachers = Teacher::with('user')
            ->get()
            ->map(function ($teacher) use ($year, $month) {
                $salary = TeacherSalary::where('teacher_id', $teacher->id)
                    ->where('year', $year)
                    ->where('month', $month)
                    ->first();

                $teacher->salary_status = $salary ? $salary->status : 'not_paid';
                $teacher->salary_details = $salary;

                return $teacher;
            });

        return Inertia::render('Admin/TeacherSalaries/Index', [
            'teachers' => $teachers,
            'year' => (int) $year,
            'month' => (int) $month,
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
        try {
            DB::beginTransaction();

            $teacher = Teacher::findOrFail($request->teacher_id);

            // Check for existing payment
            $existingPayment = TeacherSalary::forMonth($request->year, $request->month)
                ->where('teacher_id', $teacher->id)
                ->paid()
                ->exists();

            if ($existingPayment) {
                throw new \Exception('Salary already paid for this period.');
            }

            $salary = TeacherSalary::create([
                'teacher_id' => $teacher->id,
                'year' => $request->year,
                'month' => $request->month,
                'amount' => $teacher->salary_amount,
                'payment_method' => $request->payment_method,
                'receipt' => $request->file('receipt')?->store('receipts', 'public'),
            ]);

            if ($request->payment_method === 'bank') {
                $bankBalance = BankBalance::lockForUpdate()->firstOrFail();

                if ($bankBalance->balance < $salary->amount) {
                    throw new \Exception('Insufficient bank balance.');
                }

                $bankBalance->deductExpense($salary->amount);
            }

            $salary->markAsPaid();

            DB::commit();
            return back()->with('success', "Salary paid for {$salary->full_period}");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['message' => $e->getMessage()]);
        }
    }


    public function payAllTeachers(Request $request)
    {
        try {
            // Validate incoming request data
            $validated = $request->validate([
                'year' => 'required|integer',
                'month' => 'required|integer|between:1,12',
                'payment_method' => 'required|string|in:cash,bank,mobile_banking',
                'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);

            DB::beginTransaction();

            // Store the uploaded receipt (if any)
            $receiptPath = null;
            if ($request->hasFile('receipt')) {
                $receiptPath = $request->file('receipt')->store('receipts', 'public');
            }

            // Get unpaid teachers for the specified month
            $teachers = Teacher::whereNotExists(function ($query) use ($validated) {
                $query->select(DB::raw(1))
                    ->from('teacher_salaries')
                    ->whereColumn('teacher_salaries.teacher_id', 'teachers.id')
                    ->where('year', $validated['year'])
                    ->where('month', $validated['month'])
                    ->where('status', 'paid');
            })->get();

            if ($teachers->isEmpty()) {
                return response()->json([
                    'message' => 'No unpaid teachers found for the selected period.'
                ], 404);
            }

            // Calculate total required amount
            $totalAmount = $teachers->sum('salary_amount');

            // Check bank balance if payment method is bank
            if ($validated['payment_method'] === 'bank') {
                $bankBalance = BankBalance::lockForUpdate()->first();

                if (!$bankBalance || $bankBalance->balance < $totalAmount) {
                    throw new \Exception('Insufficient bank balance for processing all payments.');
                }
            }

            $processedCount = 0;
            $failedTeachers = [];

            foreach ($teachers as $teacher) {
                try {
                    // Create salary record
                    $salary = TeacherSalary::create([
                        'teacher_id' => $teacher->id,
                        'year' => $validated['year'],
                        'month' => $validated['month'],
                        'amount' => $teacher->salary_amount,
                        'payment_method' => 'cash',
                        'receipt' => $receiptPath,
                        'status' => 'paid'
                    ]);

                    // Update bank balance if payment method is bank
                    if ($validated['payment_method'] === 'bank') {
                        $bankBalance->deductExpense($teacher->salary_amount);
                    }

                    // Trigger salary paid event
                    event(new TeacherSalaryPaidEvent($salary));

                    $processedCount++;
                } catch (\Exception $e) {
                    $failedTeachers[] = [
                        'id' => $teacher->id,
                        'name' => $teacher->user->name,
                        'error' => $e->getMessage()
                    ];
                    \Log::error('Failed to process salary for teacher: ' . $teacher->id, [
                        'error' => $e->getMessage(),
                        'teacher' => $teacher->toArray()
                    ]);
                }
            }

            DB::commit();

            // Prepare response message
            $message = $processedCount . ' teacher' . ($processedCount != 1 ? 's' : '') . ' paid successfully.';
            if (!empty($failedTeachers)) {
                $message .= ' Failed to process ' . count($failedTeachers) . ' payment(s).';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'processed_count' => $processedCount,
                    'failed_teachers' => $failedTeachers,
                    'total_amount' => $totalAmount,
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Batch salary payment failed', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process salary payments: ' . $e->getMessage()
            ], 500);
        }
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
