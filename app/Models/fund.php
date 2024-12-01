<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fund extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'type',  // 'in' or 'out'
        'amount',
        'description',
        'balance',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    // Scopes
    public function scopeIncome($query)
    {
        return $query->where('type', 'in');
    }

    public function scopeExpense($query)
    {
        return $query->where('type', 'out');
    }

    public function scopeForYear($query, $year)
    {
        return $query->whereYear('date', $year);
    }

    public function scopeForMonth($query, $month)
    {
        return $query->whereMonth('date', $month);
    }

    public function scopeBeforeDate($query, $date)
    {
        return $query->where('date', '<', $date);
    }

    public function scopeAfterDate($query, $date)
    {
        return $query->where('date', '>', $date);
    }

    // Accessors & Mutators
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2);
    }

    public function getFormattedBalanceAttribute()
    {
        return number_format($this->balance, 2);
    }

    public function getFormattedDateAttribute()
    {
        return $this->date->format('F d, Y');
    }

    // Helper methods
    public static function getMonthlyBalance($year, $month)
    {
        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $income = static::income()
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $expense = static::expense()
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        return [
            'income' => $income,
            'expense' => $expense,
            'net' => $income - $expense
        ];
    }

    public static function getOpeningBalance($year, $month)
    {
        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        return static::where('date', '<', $startOfMonth)
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->value('balance') ?? 0;
    }
}
