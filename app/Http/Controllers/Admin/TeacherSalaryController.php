<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeacherSalary;
use App\Models\BankBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Carbon\Carbon;

class TeacherSalaryController extends Controller
{
    public function index(Request $request)
    {
        // Base query with relationships
        $query = TeacherSalary::with(['teacher.user'])
            ->select('teacher_salaries.*');

        // Apply filters
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Order by latest first
        $query->orderBy('year', 'desc')
            ->orderBy('month', 'desc');

        // Get paginated results
        $salaries = $query->paginate(10)->withQueryString();

        // Get current page data for statistics
        $currentPageData = $salaries->items();

        // Calculate statistics based on filtered data
        $statistics = [
            'totalTeachers' => Teacher::where('job_status', 'active')->count(),
            'totalAmount' => collect($currentPageData)->sum('amount'),
            'paidCount' => collect($currentPageData)->where('status', 'paid')->count(),
            'paidAmount' => collect($currentPageData)->where('status', 'paid')->sum('amount'),
            'pendingCount' => collect($currentPageData)->where('status', 'pending')->count(),
            'pendingAmount' => collect($currentPageData)->where('status', 'pending')->sum('amount'),
        ];

        return Inertia::render('Admin/TeacherSalaries/Index', [
            'salaries' => $salaries,
            'statistics' => $statistics,
            'filters' => [
                'year' => $request->input('year', date('Y')),
                'month' => $request->input('month', ''),
                'status' => $request->input('status', 'pending')
            ]
        ]);
    }

    public function generateMonthlySalaries()
    {
        try {
            DB::beginTransaction();

            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;

            // Check if salaries for current month already generated
            $existingSalaries = TeacherSalary::where('month', $currentMonth)
                ->where('year', $currentYear)
                ->exists();

            if ($existingSalaries) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Salaries for current month have already been generated.'
                ], 422);
            }

            // Get all active teachers
            $teachers = Teacher::where('job_status', 'active')->get();

            if ($teachers->isEmpty()) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'No active teachers found.'
                ], 422);
            }

            $generatedCount = 0;

            foreach ($teachers as $teacher) {
                // Skip teachers without salary amount
                if (!$teacher->salary_amount) {
                    continue;
                }

                TeacherSalary::create([
                    'teacher_id' => $teacher->id,
                    'year' => $currentYear,
                    'month' => $currentMonth,
                    'amount' => $teacher->salary_amount,
                    'payment_method' => 'bank_transfer',
                    'status' => 'pending'
                ]);

                $generatedCount++;
            }

            if ($generatedCount === 0) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'No salaries generated. Please check teacher salary amounts.'
                ], 422);
            }

            DB::commit();

            // Get updated data
            $query = TeacherSalary::with(['teacher.user'])
                ->where('year', $currentYear)
                ->where('month', $currentMonth);

            $salaries = $query->paginate(10);

            $statistics = $this->calculateStatistics($query);

            return response()->json([
                'success' => true,
                'message' => "Successfully generated salaries for $generatedCount teachers.",
                'salaries' => $salaries,
                'statistics' => $statistics
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error generating salaries: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while generating salaries: ' . $e->getMessage()
            ], 500);
        }
    }

    private function calculateStatistics($query)
    {
        $baseQuery = clone $query;

        return [
            'totalTeachers' => Teacher::where('job_status', 'active')->count(),
            'totalAmount' => $baseQuery->sum('amount'),
            'paidCount' => $baseQuery->where('status', 'paid')->count(),
            'paidAmount' => $baseQuery->where('status', 'paid')->sum('amount'),
            'pendingCount' => $baseQuery->where('status', 'pending')->count(),
            'pendingAmount' => $baseQuery->where('status', 'pending')->sum('amount'),
        ];
    }

    public function processSalaryPayment(TeacherSalary $salary)
    {
        try {
            DB::beginTransaction();

            // Check if salary is already paid
            if ($salary->status === 'paid') {
                return redirect()->back()
                    ->with('error', 'Salary has already been paid.');
            }

            // Get bank balance
            $bankBalance = BankBalance::first();

            // Check if sufficient balance
            if ($bankBalance->balance < $salary->amount) {
                return redirect()->back()
                    ->with('error', 'Insufficient bank balance to process salary payment.');
            }

            // Deduct from bank balance
            $bankBalance->deductExpense($salary->amount);

            // Update salary status
            $salary->update([
                'status' => 'paid',
                'payment_method' => 'bank_transfer',
                'updated_at' => now()
            ]);

            DB::commit();
            return redirect()->back()
                ->with('success', 'Salary payment processed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing salary payment: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to process salary payment. Please try again.');
        }
    }

    public function bulkProcessSalaries(Request $request)
    {
        try {
            DB::beginTransaction();

            $bankBalance = BankBalance::first();
            $pendingSalaries = TeacherSalary::where('status', 'pending')
                ->where('month', $request->month)
                ->where('year', $request->year)
                ->get();

            $totalAmount = $pendingSalaries->sum('amount');

            // Check if sufficient balance
            if ($bankBalance->balance < $totalAmount) {
                return redirect()->back()
                    ->with('error', 'Insufficient bank balance to process bulk salary payments.');
            }

            foreach ($pendingSalaries as $salary) {
                $bankBalance->deductExpense($salary->amount);
                $salary->update([
                    'status' => 'paid',
                    'payment_method' => 'bank_transfer',
                    'updated_at' => now()
                ]);
            }

            DB::commit();
            return redirect()->back()
                ->with('success', 'Bulk salary payments processed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing bulk salary payments: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to process bulk salary payments. Please try again.');
        }
    }

    public function getSalaryReport(Request $request)
    {
        $query = TeacherSalary::with('teacher.user')
            ->when($request->year, function ($q) use ($request) {
                return $q->where('year', $request->year);
            })
            ->when($request->month, function ($q) use ($request) {
                return $q->where('month', $request->month);
            })
            ->when($request->status, function ($q) use ($request) {
                return $q->where('status', $request->status);
            });

        $salaries = $query->get();

        $statistics = [
            'total_amount' => $salaries->sum('amount'),
            'paid_amount' => $salaries->where('status', 'paid')->sum('amount'),
            'pending_amount' => $salaries->where('status', 'pending')->sum('amount'),
            'total_teachers' => $salaries->count(),
            'paid_count' => $salaries->where('status', 'paid')->count(),
            'pending_count' => $salaries->where('status', 'pending')->count(),
        ];

        return Inertia::render('Admin/TeacherSalaries/Report', [
            'salaries' => $salaries,
            'statistics' => $statistics,
            'filters' => $request->only(['year', 'month', 'status'])
        ]);
    }
}
