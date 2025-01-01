<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StudentFeeController extends Controller
{
    public function index()
    {
        $fees = StudentFee::with(['student', 'schoolClass', 'section'])
            ->latest()
            ->paginate(10);

        return Inertia::render('Admin/StudentFee/Index', [
            'fees' => $fees,
            'stats' => [
                'total_due' => StudentFee::sum(DB::raw('total_fee - paid_amount')),
                'total_collected' => StudentFee::sum('paid_amount'),
                'overdue_count' => StudentFee::where('due_date', '<', now())
                    ->whereRaw('paid_amount < total_fee')
                    ->count()
            ]
        ]);
    }

    public function create()
    {
        $students = Student::with(['class', 'section'])->get()
            ->map(fn($student) => [
                'id' => $student->id,
                'name' => $student->name_en,
                'class' => $student->class?->name,
                'section' => $student->section?->name,
                'monthly_fee' => $student->monthly_fee
            ]);

        return Inertia::render('Admin/StudentFee/Create', [
            'students' => $students,
            'classes' => SchoolClass::all(),
            'sections' => Section::all(),
        ]);
    }

    public function store(Request $request)
    {
        $student = Student::findOrFail($request->student_id);

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'school_class_id' => 'required|exists:school_classes,id',
            'section_id' => 'required|exists:sections,id',
            'total_fee' => 'required|numeric|min:' . $student->monthly_fee,
            'paid_amount' => 'nullable|numeric|min:0|max:total_fee',
            'due_date' => 'required|date|after:today',
            'payment_method' => 'required|in:cash,bank,mobile_banking',
            'notes' => 'nullable|string|max:500'
        ]);

        $validated['balance'] = $validated['total_fee'] - ($validated['paid_amount'] ?? 0);
        $validated['status'] = $validated['balance'] > 0 ? 'pending' : 'paid';

        StudentFee::create($validated);

        return redirect()
            ->route('admin.student-fees.index')
            ->with('success', 'Fee record created successfully');
    }

    // Edit a student's fee
    public function edit($id)
    {
        $fee = StudentFee::findOrFail($id);
        $students = Student::all();
        $schoolClasses = SchoolClass::all();
        $sections = Section::all();

        return Inertia::render('Admin/StudentFee/Edit', [
            'fee' => $fee,
            'students' => $students,
            'schoolClasses' => $schoolClasses,
            'sections' => $sections,
        ]);
    }

    // Update the student fee
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'school_class_id' => 'required|exists:school_classes,id',
            'section_id' => 'required|exists:sections,id',
            'total_fee' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'due_date' => 'nullable|date',
        ]);

        $fee = StudentFee::findOrFail($id);
        $fee->update($validated);

        return redirect()->route('admin.student-fees.index')->with('success', 'Student fee updated successfully.');
    }

    // Delete a student fee record
    public function destroy($id)
    {
        $fee = StudentFee::findOrFail($id);
        $fee->delete();

        return redirect()->route('admin.student-fees.index')->with('success', 'Student fee deleted successfully.');
    }
}
