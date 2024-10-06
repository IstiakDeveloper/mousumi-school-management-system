<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentFee;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentFeeController extends Controller
{
    public function index()
    {
        // Fetch all student fees with their relationships (student, class, section)
        $fees = StudentFee::with(['student', 'schoolClass', 'section'])->get();

        // Pass the data to Inertia for rendering in Vue
        return Inertia::render('Admin/StudentFee/Index', [
            'fees' => $fees,
        ]);
    }

    // Show form to create a new student fee
    public function create()
    {
        // Fetch students, classes, and sections for the form
        $students = Student::all();
        $schoolClasses = SchoolClass::all();
        $sections = Section::all();

        return Inertia::render('Admin/StudentFee/Create', [
            'students' => $students,
            'schoolClasses' => $schoolClasses,
            'sections' => $sections,
        ]);
    }

    // Store a new student fee in the database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'school_class_id' => 'required|exists:school_classes,id',
            'section_id' => 'required|exists:sections,id',
            'total_fee' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'due_date' => 'nullable|date',
        ]);

        StudentFee::create($validated);

        return redirect()->route('admin.student-fees.index')->with('success', 'Student fee added successfully.');
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
