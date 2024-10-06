<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all expense categories
        $expenseCategories = ExpenseCategory::all();

        // Return the Inertia view with the list of expense categories
        return Inertia::render('Admin/ExpenseCategory/Index', [
            'expenseCategories' => $expenseCategories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Render the create form view using Inertia
        return Inertia::render('Admin/ExpenseCategory/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Create a new expense category
        ExpenseCategory::create($request->all());

        // Redirect to the index page with success message
        return redirect()->route('admin.expense-categories.index')
                         ->with('success', 'Expense Category created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExpenseCategory $expenseCategory)
    {
        // Render the edit form view using Inertia
        return Inertia::render('Admin/ExpenseCategory/Edit', [
            'expenseCategory' => $expenseCategory,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Update the expense category with validated data
        $expenseCategory->update($request->all());

        // Redirect to the index page with success message
        return redirect()->route('admin.expense-categories.index')
                         ->with('success', 'Expense Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpenseCategory $expenseCategory)
    {
        // Delete the expense category
        $expenseCategory->delete();

        // Redirect to the index page with success message
        return redirect()->route('admin.expense-categories.index')
                         ->with('success', 'Expense Category deleted successfully.');
    }
}
