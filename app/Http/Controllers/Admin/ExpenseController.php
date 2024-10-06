<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\BankBalance; // Import the BankBalance model
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('expenseCategory')->orderBy('date', 'desc')->get();

        return Inertia::render('Admin/Expenses/Index', [
            'expenses' => $expenses,
        ]);
    }

    // Show the form for creating a new expense
    public function create()
    {
        $expenseCategories = ExpenseCategory::all(); // Fetch all categories for selection

        return Inertia::render('Admin/Expenses/Create', [
            'expenseCategories' => $expenseCategories,
        ]);
    }

    // Store a newly created expense in storage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
        ]);

        // Create the expense
        $expense = Expense::create($validated);

        // Deduct from the bank balance
        $bankBalance = BankBalance::first(); // Fetch the first bank balance record
        if ($bankBalance) {
            $bankBalance->deductExpense($validated['amount']);
        }

        return redirect()->route('admin.expenses.index')->with('success', 'Expense created successfully.');
    }

    // Show the form for editing the specified expense
    public function edit(Expense $expense)
    {
        $expenseCategories = ExpenseCategory::all();

        return Inertia::render('Admin/Expenses/Edit', [
            'expense' => $expense,
            'expenseCategories' => $expenseCategories,
        ]);
    }

    // Update the specified expense in storage
    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
        ]);

        // Restore the previous amount to the bank balance
        $bankBalance = BankBalance::first();
        if ($bankBalance) {
            $bankBalance->addIncome($expense->amount); // Add back the previous expense amount
        }

        // Update the expense
        $expense->update($validated);

        // Deduct the new amount from the bank balance
        if ($bankBalance) {
            $bankBalance->deductExpense($validated['amount']);
        }

        return redirect()->route('admin.expenses.index')->with('success', 'Expense updated successfully.');
    }

    // Remove the specified expense from storage
    public function destroy(Expense $expense)
    {
        // Restore the expense amount to the bank balance
        $bankBalance = BankBalance::first();
        if ($bankBalance) {
            $bankBalance->addIncome($expense->amount); // Add back the expense amount
        }

        $expense->delete();

        return redirect()->route('admin.expenses.index')->with('success', 'Expense deleted successfully.');
    }
}
    