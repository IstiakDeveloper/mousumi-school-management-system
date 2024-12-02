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
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Load teachers with user, schoolClass, and section relationships
        $teachers = Teacher::with(['user', 'schoolClass', 'section'])->get();

        // Map through teachers and add the full image URL
        $teachers = $teachers->map(function ($teacher) {
            // If the teacher has a profile image, generate the full URL
            $teacher->profile_image_url = $teacher->profile_image
                ? asset('storage/' . $teacher->profile_image)
                : null;

            return $teacher;
        });

        // Return the teachers collection with the profile image URL to the Vue component
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'subject_specialization' => 'nullable|string|max:255',
            'pin' => 'nullable|string',
            'uid' => 'nullable|string',
            'salary_amount' => 'required|min:0',
            'dob' => 'required|date',
            'joining_date' => 'nullable|date',
            'designation' => 'required|string',
            'job_status' => 'required|in:active,inactive,on_leave,terminated',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'nullable|string|max:1000',
            'phone_number' => 'nullable|string|max:15',
        ]);

        $role = Role::firstOrCreate(['name' => 'Teacher']);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('teacherpassword'),
        ]);
        $user->assignRole($role);

        $teacherData = [
            'user_id' => $user->id,
            'pin' => $request->pin,
            'uid' => $request->uid,
            'subject_specialization' => $request->subject_specialization,
            'salary_amount' => $request->salary_amount,
            'dob' => $request->dob,
            'joining_date' => $request->joining_date,
            'designation' => $request->designation,
            'job_status' => $request->job_status,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
        ];

        if ($request->hasFile('profile_image')) {
            $teacherData['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
        }

        Teacher::create($teacherData);

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $teacher = Teacher::with(['user', 'schoolClass', 'section'])
            ->findOrFail($id);

        $teacher->profile_image_url = $teacher->profile_image
            ? asset('storage/' . $teacher->profile_image)  // Create the full URL for the image
            : null;


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
                'designation' => $teacher->designation,
                'dob' => $teacher->dob,
                'joining_date' => $teacher->joining_date,
                'job_status' => $teacher->job_status,

                'salary_amount' => $teacher->salary_amount,
                'phone_number' => $teacher->phone_number,  // Add the phone_number field
                'address' => $teacher->address,  // Add the address field
                'profile_image_url' => $teacher->profile_image_url, // Add the profile_image field
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


    public function downloadPDF(string $id, Request $request)
    {
        $teacher = Teacher::with(['user', 'schoolClass', 'section'])
            ->findOrFail($id);

        $teacher->profile_image_url = $teacher->profile_image
            ? asset('storage/' . $teacher->profile_image)
            : null;

        // Reuse the date range logic from show method
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

        // Get statistics
        $workingDays = (clone $statsQuery)
            ->whereIn('status', ['present', 'late', 'absent'])
            ->count();

        $statistics = [
            'total_days' => $workingDays,
            'present_days' => (clone $statsQuery)->where('status', 'present')->count(),
            'late_days' => (clone $statsQuery)->where('status', 'late')->count(),
            'absent_days' => (clone $statsQuery)->where('status', 'absent')->count(),
        ];

        if ($workingDays > 0) {
            $presentWeight = 1.0;
            $lateWeight = 0.7;
            $absentWeight = 0.0;

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

        // Get attendance records
        $attendances = $teacher->attendances()
            ->where(function ($query) use ($dateRangeQuery) {
                $dateRangeQuery($query);
            })
            ->latest('date')
            ->get()
            ->map(function ($attendance) {
                $duration = null;
                if ($attendance->first_attendance && $attendance->last_attendance) {
                    $first = Carbon::parse($attendance->first_attendance);
                    $last = Carbon::parse($attendance->last_attendance);
                    $duration = $last->diff($first)->format('%H:%I:%S');
                }

                return [
                    'date' => Carbon::parse($attendance->date)->format('Y-m-d'),
                    'check_in' => $attendance->first_attendance ? Carbon::parse($attendance->first_attendance)->format('H:i:s') : null,
                    'check_out' => $attendance->last_attendance ? Carbon::parse($attendance->last_attendance)->format('H:i:s') : null,
                    'duration' => $duration,
                    'total_punches' => $attendance->total_punches,
                    'status' => $attendance->status,
                ];
            });

        $pdf = PDF::loadView('pdf.teacher-report', [
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
                'phone_number' => $teacher->phone_number,
                'address' => $teacher->address,
                'profile_image_url' => $teacher->profile_image_url,
            ],
            'attendances' => $attendances,
            'statistics' => $statistics,
            'dateRange' => $request->input('dateRange', 'currentMonth'),
            'startDate' => $request->input('startDate'),
            'endDate' => $request->input('endDate'),
        ]);

        // Configure PDF options
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'defaultFont' => 'helvetica',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false,
            'isFontSubsettingEnabled' => true,
            'isPhpEnabled' => false,
        ]);

        return $pdf->download("teacher-{$teacher->id}-report.pdf");
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject_specialization' => 'nullable|string|max:255',
            'salary_amount' => 'required|numeric|min:0',
            'dob' => 'required|date',
            'joining_date' => 'nullable|date',
            'designation' => 'required|string',
            'job_status' => 'required|in:active,inactive,on_leave,terminated',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'nullable|string|max:1000',
            'phone_number' => 'nullable|string|max:15',
        ]);

        $teacher = Teacher::with('user')->findOrFail($id);

        $teacher->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->hasFile('profile_image')) {
            if ($teacher->profile_image) {
                Storage::delete('public/' . $teacher->profile_image);
            }
            $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
        } else {
            $profileImagePath = $teacher->profile_image;
        }

        $teacher->update([
            'subject_specialization' => $request->subject_specialization,
            'salary_amount' => $request->salary_amount,
            'dob' => $request->dob,
            'joining_date' => $request->joining_date,
            'designation' => $request->designation,
            'job_status' => $request->job_status,
            'profile_image' => $profileImagePath,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
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
