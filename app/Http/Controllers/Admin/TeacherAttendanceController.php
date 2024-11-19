<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class TeacherAttendanceController extends Controller
{
    public function fetch()
    {
        try {
            $response = Http::get('https://socially-striking-squid.ngrok-free.app/api/attendance-logs');

            if (!$response->successful()) {
                return back()->with('error', 'Failed to fetch attendance data');
            }

            $attendanceLogs = $response->json();
            $processedTeachers = [];
            $errors = [];

            foreach ($attendanceLogs as $log) {
                try {
                    // Changed from uid to id for matching
                    $teacher = Teacher::where('uid', $log['id'])->first();

                    if (!$teacher) {
                        $errors[] = "No teacher found with ID: {$log['id']} ({$log['name']})";
                        continue;
                    }

                    // Process each attendance log
                    $attendance = TeacherAttendance::processAttendanceLog($log, $teacher);

                    $processedTeachers[$teacher->id] = [
                        'name' => $teacher->user->name,
                        'date' => Carbon::parse($log['timestamp'])->toDateString(),
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

    public function index()
    {
        return Inertia::render('Admin/TeacherAttendance/Index', [
            'attendances' => TeacherAttendance::with('teacher.user')
                ->latest('date')
                ->paginate(10)
                ->through(fn ($attendance) => [
                    'id' => $attendance->id,
                    'teacher_id' => $attendance->teacher_id,
                    'teacher_name' => $attendance->teacher->user->name,
                    'date' => $attendance->date->format('Y-m-d'),
                    'check_in' => $attendance->first_attendance?->format('H:i:s'),
                    'check_out' => $attendance->last_attendance?->format('H:i:s'),
                    'duration' => $attendance->duration,
                    'total_punches' => $attendance->total_punches,
                    'status' => $attendance->status,
                ]),
            'filters' => request()->only(['search', 'date']),
            'flash' => [
                'success' => session('success'),
                'error' => session('error')
            ],
        ]);
    }
}
