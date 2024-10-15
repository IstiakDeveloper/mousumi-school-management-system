<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = ['student_id', 'month', 'year', 'amount', 'status', 'payment_method', 'receipt'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
