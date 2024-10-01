<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeBook extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'exam_category_id',
        'school_class_id',
        'section_id',
        'subject_id',
        'mark',
        'grade_id',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function examCategory()
    {
        return $this->belongsTo(ExamCategory::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    // Function to calculate and assign the grade based on the mark
    public static function calculateGrade($mark)
    {
        return Grade::where('mark_from', '<=', $mark)
                    ->where('mark_upto', '>=', $mark)
                    ->first();
    }
}
