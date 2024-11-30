<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankBalance;
use App\Models\fund;
use Illuminate\Http\Request;

class FundController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Query to filter funds by year and month if needed
        $funds = Fund::query();

        if ($request->year) {
            $funds->whereYear('created_at', $request->year);
        }

        if ($request->month) {
            $funds->whereMonth('created_at', $request->month);
        }

        $funds = $funds->orderBy('created_at', 'desc')->get();

        // Get the current bank balance
        $bankBalance = BankBalance::first()->balance;

        return inertia('Admin/FundManagement/Index', [
            'funds' => $funds,
            'years' => Fund::selectRaw('YEAR(created_at) as year')->distinct()->pluck('year'),
            'months' => collect(range(1, 12))->mapWithKeys(function ($month) {
                return [$month => \DateTime::createFromFormat('!m', $month)->format('F')];
            }),
            'bankBalance' => $bankBalance,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Admin/FundManagement/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:in,out',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        // Create a new Fund transaction
        $fund = Fund::create([
            'date' => $request->date,
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'balance' => 0,  // Optional: you can compute the balance if necessary
        ]);

        // Update the bank balance accordingly
        $bankBalance = BankBalance::first();

        if ($fund->type === 'in') {
            $bankBalance->addIncome($fund->amount);
        } else {
            $bankBalance->deductExpense($fund->amount);
        }

        return redirect()->route('admin.funds.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
