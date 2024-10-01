<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamCategory;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all exams with related category, class, and subject for listing
        $exams = Exam::with(['examCategory', 'schoolClass', 'subject'])->get();

        // Return the view using Inertia
        return Inertia::render('Admin/Exam/Index', [
            'exams' => $exams,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Fetch exam categories, school classes, and subjects for dropdowns in the create form
        $examCategories = ExamCategory::all();
        $schoolClasses = SchoolClass::all();
        $subjects = Subject::all();

        // Return the form using Inertia
        return Inertia::render('Admin/Exam/Create', [
            'examCategories' => $examCategories,
            'schoolClasses' => $schoolClasses,
            'subjects' => $subjects,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'exam_category_id' => 'required|exists:exam_categories,id',
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'starting_date' => 'required|date',
            'starting_time' => 'required|date_format:H:i',
            'ending_date' => 'nullable|date',
            'ending_time' => 'nullable|date_format:H:i',
            'total_marks' => 'required|integer',
        ]);

        // Create a new exam record
        Exam::create($validated);

        // Redirect with a success message
        return redirect()->route('admin.exams.index')->with('success', 'Exam created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        // Find the exam by ID
        $exam = Exam::with(['examCategory', 'schoolClass', 'subject'])->findOrFail($id);

        // Fetch exam categories, school classes, and subjects for dropdowns in the edit form
        $examCategories = ExamCategory::all();
        $schoolClasses = SchoolClass::all();
        $subjects = Subject::all();

        // Return Inertia component and pass the exam and dropdown data
        return Inertia::render('Admin/Exam/Edit', [
            'exam' => $exam,
            'examCategories' => $examCategories,
            'schoolClasses' => $schoolClasses,
            'subjects' => $subjects,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        // dd($request->all());

        // Validate the incoming request data
        $validated = $request->validate([
            'exam_category_id' => 'required|exists:exam_categories,id',
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'starting_date' => 'required',
            'starting_time' => 'required',
            'ending_date' => 'nullable',
            'ending_time' => 'nullable',
            'total_marks' => 'required|integer',
        ]);

        // Find the exam by ID
        $exam = Exam::findOrFail($id); // Ensure to handle the case where the exam does not exist

        // Update the exam with validated data
        $exam->update($validated);

        // Redirect back with a success message or return a JSON response
        return redirect()->route('admin.exams.index')->with('success', 'Exam updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the exam by ID and delete it
        $exam = Exam::findOrFail($id);
        $exam->delete();

        return redirect()->route('admin.exams.index')->with('success', 'Exam deleted successfully.');
    }
}
