<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExamCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all exam categories
        $examCategories = ExamCategory::all();

        // dd($categories);
        // Return view using Inertia
        return Inertia::render('Admin/ExamCategory/Index', [
            'examCategories' => $examCategories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Return form using Inertia
        return Inertia::render('Admin/ExamCategory/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
        ]);

        // Create a new exam category record
        ExamCategory::create($validated);

        // Redirect with a success message
        return redirect()->route('admin.exam-categories.index')->with('success', 'Exam category created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Find the exam category by ID
        $examCategory = ExamCategory::findOrFail($id);

        // Return Inertia component and pass the category
        return Inertia::render('Admin/ExamCategory/Edit', [
            'examCategory' => $examCategory,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        // Find the exam category by ID
        $category = ExamCategory::findOrFail($id);

        // Update the exam category record
        $category->update($validated);

        return redirect()->route('admin.exam-categories.index')->with('success', 'Exam category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the exam category by ID and delete it
        $category = ExamCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.exam-categories.index')->with('success', 'Exam category deleted successfully.');
    }
}
