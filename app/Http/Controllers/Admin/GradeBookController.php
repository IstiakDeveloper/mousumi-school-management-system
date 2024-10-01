<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamCategory;
use App\Models\GradeBook;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GradeBookController extends Controller
{
    public function create()
    {
        // Fetch necessary data to populate select fields in the form
        $examCategories = ExamCategory::all();
        $schoolClasses = SchoolClass::all();
        $sections = Section::all();
        $subjects = Subject::all();

        return Inertia::render('Admin/GradeBook/Create', [
            'examCategories' => $examCategories,
            'schoolClasses' => $schoolClasses,
            'sections' => $sections,
            'subjects' => $subjects,
        ]);
    }

    /**
     * Display students based on selected exam, class, section, and subject.
     */
    public function showStudents(Request $request)
    {
        $validated = $request->validate([
            'exam_category_id' => 'required|exists:exam_categories,id',
            'school_class_id' => 'required|exists:school_classes,id',
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        // Retrieve students based on class and section
        $students = Student::where('class_id', $validated['school_class_id'])
            ->where('section_id', $validated['section_id'])
            ->get();

        // Ensure students are retrieved; if none found, return an appropriate message
        if ($students->isEmpty()) {
            return Inertia::render('Admin/GradeBook/Create', [
                'examCategories' => ExamCategory::all(),
                'schoolClasses' => SchoolClass::all(),
                'sections' => Section::all(),
                'subjects' => Subject::all(),
                'errorMessage' => 'No students found for the selected class and section.',
            ]);
        }

        return Inertia::render('Admin/GradeBook/EnterMarks', [
            'students' => $students,
            'examCategoryId' => $validated['exam_category_id'],
            'schoolClassId' => $validated['school_class_id'],
            'sectionId' => $validated['section_id'],
            'subjectId' => $validated['subject_id'],
            'examCategoryName' => ExamCategory::find($validated['exam_category_id'])->title,
            'schoolClassName' => SchoolClass::find($validated['school_class_id'])->name,
            'sectionName' => Section::find($validated['section_id'])->name,
            'subjectName' => Subject::find($validated['subject_id'])->name,
        ]);
    }

    /**
     * Store marks for students and calculate their grade.
     */
    public function storeMarks(Request $request)
    {
        $validated = $request->validate([
            'students' => 'required|array', // An array of students' marks
            'students.*.student_id' => 'required|exists:students,id',
            'students.*.mark' => 'required|integer|min:0|max:100',
            'exam_category_id' => 'required|exists:exam_categories,id',
            'school_class_id' => 'required|exists:school_classes,id',
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        foreach ($validated['students'] as $studentMark) {
            // Calculate the grade based on the mark
            $grade = GradeBook::calculateGrade($studentMark['mark']);

            // Update or create the student's marks and grade in the grade book
            GradeBook::updateOrCreate(
                [
                    'student_id' => $studentMark['student_id'],
                    'exam_category_id' => $validated['exam_category_id'],
                    'school_class_id' => $validated['school_class_id'],
                    'section_id' => $validated['section_id'],
                    'subject_id' => $validated['subject_id'],
                ],
                [
                    'mark' => $studentMark['mark'],
                    'grade_id' => $grade ? $grade->id : null, // Link the correct grade
                ]
            );
        }

        return redirect()->route('admin.gradebooks.index')
            ->with('success', 'Marks and grades have been saved successfully.');
    }

    /**
     * Display the results for a particular exam category, class, and section.
     */
    public function index()
    {
        // Fetch data for filters
        $examCategories = ExamCategory::all();
        $schoolClasses = SchoolClass::all();
        $sections = Section::all();
        $subjects = Subject::all();
        $students = Student::all();

        // Fetch all students and their corresponding marks and grades
        $gradeBooks = GradeBook::with(['student', 'grade'])->get();

        return Inertia::render('Admin/GradeBook/Index', [
            'gradeBooks' => $gradeBooks,
            'examCategories' => $examCategories,
            'schoolClasses' => $schoolClasses,
            'sections' => $sections,
            'subjects' => $subjects,
            'students' => $students,
        ]);
    }
}
