<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;
    protected $fillable = [
        'exam_category_id',
        'class_id',
        'subject_id',
        'starting_date',
        'starting_time',
        'ending_date',
        'ending_time',
        'total_marks',
    ];

    public function category()
    {
        return $this->belongsTo(ExamCategory::class);
    }
    public function examCategory()
    {
        return $this->belongsTo(ExamCategory::class);
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class);
    }
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
