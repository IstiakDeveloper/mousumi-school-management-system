<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accounting extends Model
{
    use HasFactory;
    protected $fillable = ['student_id', 'amount', 'description', 'date_of_payment'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
