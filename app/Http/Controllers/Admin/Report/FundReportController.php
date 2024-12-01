<?php

namespace App\Http\Controllers\Admin\Report;

use App\Exports\FundReportExport;
use App\Http\Controllers\Controller;
use App\Models\Fund;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class FundReportController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        // Get monthly summaries for the selected year
        $monthlyReports = $this->getMonthlyReports($year);

        // Get detailed report for selected month
        $selectedMonthReport = $this->getDetailedMonthReport($year, $month);

        return inertia('Admin/Report/FundReport', [
            'monthlyReports' => $monthlyReports,
            'selectedMonthReport' => $selectedMonthReport,
            'availableYears' => $this->getAvailableYears(),
            'selectedYear' => (int)$year,
            'selectedMonth' => (int)$month,
            'statistics' => $this->getStatistics($year),
        ]);
    }

    private function getAvailableYears()
    {
        $years = Fund::selectRaw('DISTINCT YEAR(date) as year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        return $years->isEmpty() ? [now()->year] : $years;
    }

    private function getStatistics($year)
    {
        return [
            'yearlyIncome' => Fund::whereYear('date', $year)
                ->where('type', 'in')
                ->sum('amount'),
            'yearlyExpense' => Fund::whereYear('date', $year)
                ->where('type', 'out')
                ->sum('amount'),
            'totalTransactions' => Fund::whereYear('date', $year)->count(),
            'averageMonthlyIncome' => Fund::whereYear('date', $year)
                ->where('type', 'in')
                ->avg('amount') ?? 0,
        ];
    }

    private function getMonthlyReports($year)
    {
        // Get the balance at the start of the year
        $startingBalance = Fund::whereDate('date', '<', "$year-01-01")
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->value('balance') ?? 0;

        $monthlyData = Fund::whereYear('date', $year)
            ->select(
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(CASE WHEN type = "in" THEN amount ELSE 0 END) as total_income'),
                DB::raw('SUM(CASE WHEN type = "out" THEN amount ELSE 0 END) as total_expense')
            )
            ->groupBy(DB::raw('MONTH(date)'))
            ->orderBy('month')
            ->get();

        $runningBalance = $startingBalance;
        $reports = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthData = $monthlyData->firstWhere('month', $month);

            $income = $monthData ? $monthData->total_income : 0;
            $expense = $monthData ? $monthData->total_expense : 0;

            $reports[] = [
                'month' => $month,
                'month_name' => Carbon::create()->month($month)->format('F'),
                'opening_balance' => $runningBalance,
                'total_income' => $income,
                'total_expense' => $expense,
                'net_flow' => $income - $expense,
                'closing_balance' => $runningBalance + ($income - $expense)
            ];

            $runningBalance += ($income - $expense);
        }

        return $reports;
    }

    private function getDetailedMonthReport($year, $month)
    {
        // Get opening balance for the month
        $openingBalance = Fund::whereDate('date', '<', "$year-$month-01")
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->value('balance') ?? 0;

        $transactions = Fund::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        $runningBalance = $openingBalance;
        $processedTransactions = [];

        foreach ($transactions as $transaction) {
            $runningBalance += ($transaction->type === 'in' ? $transaction->amount : -$transaction->amount);

            $processedTransactions[] = [
                'id' => $transaction->id,
                'date' => $transaction->date,
                'description' => $transaction->description,
                'type' => $transaction->type,
                'amount' => $transaction->amount,
                'running_balance' => $runningBalance
            ];
        }

        return [
            'opening_balance' => $openingBalance,
            'closing_balance' => $runningBalance,
            'total_income' => $transactions->where('type', 'in')->sum('amount'),
            'total_expense' => $transactions->where('type', 'out')->sum('amount'),
            'transaction_count' => $transactions->count(),
            'transactions' => $processedTransactions
        ];
    }

    public function export(Request $request)
    {
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        // Get detailed report for the month
        $monthReport = $this->getDetailedMonthReport($year, $month);

        $fileName = "fund_report_{$year}_{$month}.xlsx";

        return Excel::download(new FundReportExport(
            $monthReport['transactions'],
            [
                'opening_balance' => $monthReport['opening_balance'],
                'closing_balance' => $monthReport['closing_balance'],
                'total_income' => $monthReport['total_income'],
                'total_expense' => $monthReport['total_expense']
            ],
            $year,
            $month
        ), $fileName);
    }
}
