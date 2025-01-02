<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class TeacherSalary extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'year',
        'month',
        'payment_method',
        'receipt',
        'amount',
        'status',
    ];

    protected $casts = [
        'year' => 'integer',
        'month' => 'integer',
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the teacher that owns the salary.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Scope a query to only include paid salaries.
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope a query to only include pending salaries.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to filter by year and month.
     */
    public function scopeForMonth($query, $year, $month)
    {
        return $query->where('year', $year)
                    ->where('month', $month);
    }

    /**
     * Check if the salary is paid.
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Get the formatted month name.
     */
    public function getMonthNameAttribute(): string
    {
        return Carbon::create()->month($this->month)->format('F');
    }

    /**
     * Get the formatted amount with currency.
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2);
    }

    /**
     * Get full period (e.g., "January 2024")
     */
    public function getFullPeriodAttribute(): string
    {
        return $this->month_name . ' ' . $this->year;
    }

    /**
     * Mark salary as paid
     */
    public function markAsPaid(): bool
    {
        return $this->update(['status' => 'paid']);
    }

    /**
     * Mark salary as pending
     */
    public function markAsPending(): bool
    {
        return $this->update(['status' => 'pending']);
    }

    /**
     * Get the receipt URL if exists
     */
    public function getReceiptUrlAttribute(): ?string
    {
        return $this->receipt ? asset('storage/' . $this->receipt) : null;
    }

    /**
     * Check if salary is overdue
     */
    public function isOverdue(): bool
    {
        $salaryDate = Carbon::create($this->year, $this->month, 1)->endOfMonth();
        return $this->status !== 'paid' && $salaryDate->isPast();
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($salary) {
            // Ensure proper status on creation
            if (!$salary->status) {
                $salary->status = 'pending';
            }
        });

        static::updating(function ($salary) {
            // Add any validation or checks before update
            if ($salary->isDirty('status') && $salary->status === 'paid') {
                // You could trigger notifications here
                // event(new SalaryPaidEvent($salary));
            }
        });
    }
}
