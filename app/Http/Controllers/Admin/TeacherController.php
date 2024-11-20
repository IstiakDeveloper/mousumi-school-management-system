<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Barryvdh\DomPDF\Facade\Pdf;


class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = Teacher::with(['user', 'schoolClass', 'section'])->get();
        return Inertia::render('Admin/Teacher/Index', compact('teachers'));
    }

    public function create()
    {
        $classes = SchoolClass::all();
        $sections = Section::all();

        return Inertia::render('Admin/Teacher/Create', [
            'classes' => $classes,
            'sections' => $sections,
        ]);
    }

    public function store(Request $request)
    {
        // Validate the request and return errors if validation fails
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'subject_specialization' => 'required|string|max:255',
            'class_id' => 'nullable|exists:school_classes,id',
            'pin' => 'nullable|numeric',
            'uid' => 'nullable|numeric',
            'section_id' => 'nullable|exists:sections,id',
            'salary_amount' => 'required||min:0', // New validation for salary
        ]);

        // Create a new role for the teacher and assign the role
        $role = Role::firstOrCreate(['name' => 'Teacher']);

        // Create the user with a default password
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('teacherpassword'), // Default password
        ]);
        $user->assignRole($role);

        // Create the teacher model associated with the user
        Teacher::create([
            'user_id' => $user->id,
            'pin' => $request->pin,
            'uid' => $request->uid,
            'subject_specialization' => $request->subject_specialization,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'salary_amount' => $request->salary_amount, // Store the salary amount
        ]);

        // Redirect back with success message
        return redirect()->route('admin.teachers.index')->with('success', 'Teacher created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $teacher = Teacher::with(['user', 'schoolClass', 'section'])
            ->findOrFail($id);

        // Base query for date range
        $dateRangeQuery = function ($query) use ($request) {
            $dateRange = $request->input('dateRange', 'currentMonth');
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');

            switch ($dateRange) {
                case 'currentMonth':
                    $query->whereBetween('date', [
                        Carbon::now()->startOfMonth(),
                        Carbon::now()->endOfMonth()
                    ]);
                    break;
                case 'lastMonth':
                    $query->whereBetween('date', [
                        Carbon::now()->subMonth()->startOfMonth(),
                        Carbon::now()->subMonth()->endOfMonth()
                    ]);
                    break;
                case 'last3Months':
                    $query->whereBetween('date', [
                        Carbon::now()->subMonths(3)->startOfMonth(),
                        Carbon::now()->endOfMonth()
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
        };

        $statsQuery = $teacher->attendances()->where(function ($query) use ($dateRangeQuery) {
            $dateRangeQuery($query);
        });

        // Get the total working days in the selected period
        $workingDays = (clone $statsQuery)
            ->whereIn('status', ['present', 'late', 'absent'])
            ->count();

        // Calculate attendance statistics
        $statistics = [
            'total_days' => $workingDays,
            'present_days' => (clone $statsQuery)->where('status', 'present')->count(),
            'late_days' => (clone $statsQuery)->where('status', 'late')->count(),
            'absent_days' => (clone $statsQuery)->where('status', 'absent')->count(),
        ];

        // Calculate attendance percentage with proper weighting
        if ($workingDays > 0) {
            $presentWeight = 1.0; // 100% weight for present days
            $lateWeight = 0.7;    // 70% weight for late days
            $absentWeight = 0.0;  // 0% weight for absent days

            $weightedAttendance =
                ($statistics['present_days'] * $presentWeight) +
                ($statistics['late_days'] * $lateWeight) +
                ($statistics['absent_days'] * $absentWeight);

            $statistics['attendance_percentage'] = round(
                ($weightedAttendance / $workingDays) * 100,
                2
            );
        } else {
            $statistics['attendance_percentage'] = 0;
        }

        // Add more detailed statistics
        $statistics['attendance_summary'] = [
            'present_percentage' => $workingDays > 0
                ? round(($statistics['present_days'] / $workingDays) * 100, 2)
                : 0,
            'late_percentage' => $workingDays > 0
                ? round(($statistics['late_days'] / $workingDays) * 100, 2)
                : 0,
            'absent_percentage' => $workingDays > 0
                ? round(($statistics['absent_days'] / $workingDays) * 100, 2)
                : 0,
        ];

        // Get attendance records with a fresh query
        $attendances = $teacher->attendances()
            ->where(function ($query) use ($dateRangeQuery) {
                $dateRangeQuery($query);
            })
            ->latest('date')
            ->paginate(100)
            ->through(function ($attendance) {
                $duration = null;
                if ($attendance->first_attendance && $attendance->last_attendance) {
                    $first = Carbon::parse($attendance->first_attendance);
                    $last = Carbon::parse($attendance->last_attendance);
                    $duration = $last->diff($first)->format('%H:%I:%S');
                }

                // Handle attendance_logs properly
                $attendanceLogs = $attendance->attendance_logs;
                if (is_string($attendanceLogs)) {
                    try {
                        $attendanceLogs = json_decode($attendanceLogs, true) ?? [];
                    } catch (\Exception $e) {
                        $attendanceLogs = [];
                    }
                } elseif (!is_array($attendanceLogs)) {
                    $attendanceLogs = [];
                }

                return [
                    'id' => $attendance->id,
                    'date' => Carbon::parse($attendance->date)->format('Y-m-d'),
                    'check_in' => $attendance->first_attendance ? Carbon::parse($attendance->first_attendance)->format('H:i:s') : null,
                    'check_out' => $attendance->last_attendance ? Carbon::parse($attendance->last_attendance)->format('H:i:s') : null,
                    'duration' => $duration,
                    'total_punches' => $attendance->total_punches,
                    'status' => $attendance->status,
                    'attendance_logs' => $attendanceLogs,
                ];
            });

        return Inertia::render('Admin/Teacher/Show', [
            'teacher' => [
                'id' => $teacher->id,
                'name' => $teacher->user->name,
                'email' => $teacher->user->email,
                'pin' => $teacher->pin,
                'uid' => $teacher->uid,
                'subject_specialization' => $teacher->subject_specialization,
                'class' => $teacher->schoolClass ? $teacher->schoolClass->name : null,
                'section' => $teacher->section ? $teacher->section->name : null,
                'salary_amount' => $teacher->salary_amount,
            ],
            'attendances' => $attendances,
            'statistics' => $statistics,
            'filters' => [
                'dateRange' => $request->input('dateRange', 'currentMonth'),
                'startDate' => $request->input('startDate'),
                'endDate' => $request->input('endDate'),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $teacher = Teacher::with('user')->findOrFail($id);
        $classes = SchoolClass::all();
        $sections = Section::all();

        return Inertia::render('Admin/Teacher/Edit', [
            'teacher' => $teacher,
            'classes' => $classes,
            'sections' => $sections,
        ]);
    }

    public function update(Request $request, string $id)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255,' . $id, // Ensure unique email for the same user
            'subject_specialization' => 'required|string|max:255',
            'class_id' => 'nullable|exists:school_classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'salary_amount' => 'required|numeric|min:0', // New validation for salary
        ]);

        $teacher = Teacher::with('user')->findOrFail($id);

        // Update the user's details
        $teacher->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update the teacher's details
        $teacher->update([
            'subject_specialization' => $request->subject_specialization,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'salary_amount' => $request->salary_amount, // Update the salary amount
        ]);

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        // Delete the user and parent model
        $teacher->user->delete();
        $teacher->delete();

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher deleted successfully!');
    }
}
