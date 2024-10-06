<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankBalance;
use App\Models\Expense;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Get the bank balance
        $bankBalance = BankBalance::first();

        // Get the total expenses
        $totalExpenses = Expense::sum('amount');

        // Fetch recent expenses for display
        $recentExpenses = Expense::with('expenseCategory')
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'bankBalance' => $bankBalance ? $bankBalance->balance : 0,
            'totalExpenses' => $totalExpenses,
            'recentExpenses' => $recentExpenses,
        ]);
    }
}
