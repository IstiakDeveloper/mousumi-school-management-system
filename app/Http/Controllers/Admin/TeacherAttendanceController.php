<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class TeacherAttendanceController extends Controller
{
    public function fetch()
    {
        try {
            $response = Http::get('https://caring-bird-finally.ngrok-free.app/api/attendance-logs');

            if (!$response->successful()) {
                return back()->with('error', 'Failed to fetch attendance data');
            }

            $attendanceLogs = $response->json();
            $processedTeachers = [];
            $errors = [];
            $processedDates = [];

            foreach ($attendanceLogs as $log) {
                try {
                    $teacher = Teacher::where('uid', $log['id'])
                        ->where('job_status', 'active')
                        ->first();

                    if (!$teacher) {
                        $errors[] = "No active teacher found with ID: {$log['id']} ({$log['name']})";
                        continue;
                    }

                    $attendance = TeacherAttendance::processAttendanceLog($log, $teacher);
                    $date = Carbon::parse($log['timestamp'])->toDateString();

                    $processedDates[$date] = true;
                    $processedTeachers[$teacher->id] = [
                        'name' => $teacher->user->name,
                        'date' => $date,
                        'status' => $attendance->status
                    ];
                } catch (\Exception $e) {
                    $errors[] = "Error processing log for ID {$log['id']}: " . $e->getMessage();
                    Log::error('Attendance Processing Error', [
                        'log_id' => $log['id'],
                        'error' => $e->getMessage(),
                        'log_data' => $log
                    ]);
                }
            }

            // Process absents for all processed dates
            foreach (array_keys($processedDates) as $date) {
                $date = Carbon::parse($date);
                if (!$date->isFriday() && !$date->isSaturday()) {
                    TeacherAttendance::markAbsentForDate($date);
                }
            }

            $processedCount = count($processedTeachers);
            $errorCount = count($errors);

            if ($errorCount > 0) {
                session()->flash('error', implode("\n", $errors));
            }

            if ($processedCount > 0) {
                session()->flash('success', "Successfully processed attendance for {$processedCount} teachers.");
            } elseif ($processedCount === 0 && $errorCount === 0) {
                session()->flash('error', 'No matching teachers found in the system.');
            }

            return back();
        } catch (\Exception $e) {
            Log::error('Attendance Fetch Error', [
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Failed to process attendance data: ' . $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        $query = TeacherAttendance::with('teacher.user');

        // Handle date filtering
        $dateRange = $request->input('dateRange', 'today');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $specificDate = $request->input('specificDate');

        switch ($dateRange) {
            case 'specificDay':
                if ($specificDate) {
                    $query->whereDate('date', Carbon::parse($specificDate));
                }
                break;
            case 'today':
                $query->whereDate('date', Carbon::today());
                break;
            case 'last7':
                $query->whereBetween('date', [
                    Carbon::now()->subDays(6)->startOfDay(),
                    Carbon::now()->endOfDay()
                ]);
                break;
            case 'lastMonth':
                $query->whereBetween('date', [
                    Carbon::now()->subMonth()->startOfMonth(),
                    Carbon::now()->subMonth()->endOfMonth()
                ]);
                break;
            case 'custom':
                if ($startDate && $endDate) {
                    $query->whereBetween('date', [
                        Carbon::parse($startDate)->startOfDay(),
                        Carbon::parse($endDate)->endOfDay()
                    ]);
                }
                break;
        }

        // Handle search
        if ($search = $request->input('search')) {
            $query->whereHas('teacher.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Add sorting
        $query->latest('date');

        // Get paginated results with all query parameters preserved
        $attendances = $query->paginate(10)
            ->through(function ($attendance) {
                $duration = null;
                if ($attendance->first_attendance && $attendance->last_attendance) {
                    $first = Carbon::parse($attendance->first_attendance);
                    $last = Carbon::parse($attendance->last_attendance);
                    $duration = $last->diff($first)->format('%H:%I:%S');
                }

                return [
                    'id' => $attendance->id,
                    'teacher_id' => $attendance->teacher_id,
                    'teacher_name' => $attendance->teacher->user->name,
                    'date' => Carbon::parse($attendance->date)->format('Y-m-d'),
                    'check_in' => $attendance->first_attendance ? Carbon::parse($attendance->first_attendance)->format('H:i:s') : null,
                    'check_out' => $attendance->last_attendance ? Carbon::parse($attendance->last_attendance)->format('H:i:s') : null,
                    'duration' => $duration,
                    'total_punches' => $attendance->total_punches,
                    'status' => $attendance->status,
                ];
            })
            ->withQueryString(); // This preserves all query parameters in pagination links

        return Inertia::render('Admin/TeacherAttendance/Index', [
            'attendances' => $attendances,
            'filters' => [
                'search' => $request->input('search'),
                'dateRange' => $dateRange,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'specificDate' => $specificDate,
            ],
            'flash' => [
                'success' => session('success'),
                'error' => session('error')
            ],
        ]);
    }
}
