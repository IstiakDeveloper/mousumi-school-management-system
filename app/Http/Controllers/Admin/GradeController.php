<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all grades
        $grades = Grade::all();

        // Return the view using Inertia
        return Inertia::render('Admin/Grade/Index', [
            'grades' => $grades,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Return the create form using Inertia
        return Inertia::render('Admin/Grade/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'grade' => 'required|string|max:255',
            'grade_point' => 'required|numeric|min:0|max:10',
            'mark_from' => 'required|integer|min:0|max:100',
            'mark_upto' => 'required|integer|min:0|max:100|gte:mark_from', // Ensure mark_upto is greater than or equal to mark_from
        ]);

        // Create a new grade record
        Grade::create($validated);

        // Redirect with a success message
        return redirect()->route('admin.grades.index')->with('success', 'Grade created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Find the grade by ID
        $gradeData = Grade::findOrFail($id);

        // Return the edit form using Inertia
        return Inertia::render('Admin/Grade/Edit', [
            'gradeData' => $gradeData,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'grade' => 'required|string|max:255',
            'grade_point' => 'required|numeric|min:0|max:10',
            'mark_from' => 'required|integer|min:0|max:100',
            'mark_upto' => 'required|integer|min:0|max:100|gte:mark_from', // Ensure mark_upto is greater than or equal to mark_from
        ]);

        // Find the grade by ID
        $grade = Grade::findOrFail($id);

        // Update the grade with validated data
        $grade->update($validated);

        // Redirect back with a success message
        return redirect()->route('admin.grades.index')->with('success', 'Grade updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the grade by ID and delete it
        $grade = Grade::findOrFail($id);
        $grade->delete();

        // Redirect with a success message
        return redirect()->route('admin.grades.index')->with('success', 'Grade deleted successfully.');
    }
}
