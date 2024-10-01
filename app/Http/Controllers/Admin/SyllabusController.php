<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Syllabus;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class SyllabusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all syllabus with related class and subject
        $syllabuses = Syllabus::with(['class', 'subject'])->get();

        // Return view using Inertia
        return Inertia::render('Admin/Syllabus/Index', [
            'syllabuses' => $syllabuses,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Fetch all classes and subjects for the form
        $classes = SchoolClass::all();
        $subjects = Subject::all();

        // Return form using Inertia
        return Inertia::render('Admin/Syllabus/Create', [
            'classes' => $classes,
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
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'description' => 'nullable|string',
            'pdf_file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        // Handle file upload if PDF is provided
        if ($request->hasFile('pdf_file')) {
            // Store the file and get its path
            $filePath = $request->file('pdf_file')->store('syllabus_files', 'public');

            // Add the file path to the validated data
            $validated['pdf_file'] = $filePath;
        }

        // Create a new syllabus record using the validated data
        Syllabus::create($validated);

        // Redirect with a success message
        return redirect()->route('admin.syllabus.index')->with('success', 'Syllabus created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Syllabus $syllabus)
    {
        // Return the syllabus details using Inertia
        return Inertia::render('Admin/Syllabus/Show', [
            'syllabus' => $syllabus,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Find the syllabus with the associated class and subject
        $syllabus = Syllabus::with('class', 'subject')->findOrFail($id);

        // Retrieve all classes and subjects for selection
        $classes = SchoolClass::all();
        $subjects = Subject::all();

        // Return Inertia component and pass the syllabus, classes, and subjects
        return Inertia::render('Admin/Syllabus/Edit', [
            'syllabus' => $syllabus,
            'classes' => $classes,
            'subjects' => $subjects,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Syllabus $syllabus)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'class_id' => 'exists:school_classes,id',
            'subject_id' => 'exists:subjects,id',
            'description' => 'nullable|string',
            'pdf_file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        // Handle file upload if PDF is provided
        if ($request->hasFile('pdf_file')) {
            // Delete the old file if it exists
            if ($syllabus->pdf_file && Storage::disk('public')->exists($syllabus->pdf_file)) {
                Storage::disk('public')->delete($syllabus->pdf_file);
            }
            $filePath = $request->file('pdf_file')->store('syllabus_files', 'public');
            $validated['pdf_file'] = $filePath;
        }


        // Update the syllabus record
        $syllabus->update($validated);

        return redirect()->route('admin.syllabus.index')->with('success', 'Syllabus updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Syllabus $syllabus)
    {
        // Delete the PDF file if it exists
        if ($syllabus->pdf_file && Storage::disk('public')->exists($syllabus->pdf_file)) {
            Storage::disk('public')->delete($syllabus->pdf_file);
        }

        // Delete the syllabus record
        $syllabus->delete();

        return redirect()->route('admin.syllabus.index')->with('success', 'Syllabus deleted successfully.');
    }
}
