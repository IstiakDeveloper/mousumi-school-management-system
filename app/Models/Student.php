<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'user_id',
        'class_id',
        'section_id',
        'parent_id',
        'photo',
        'student_id',
        'form_number',
        'monthly_fee',
        'name_bn',
        'name_en',
        'birth_certificate_number',
        'birth_place_district',
        'date_of_birth',
        'gender',
        'nationality',
        'religion',
        'blood_group',
        'class_role',
        'minorities',
        'minority_name',
        'handicap',
        'mother_nid',
        'mother_dob',
        'mother_name_bn',
        'mother_name_en',
        'mother_mobile',
        'mother_occupation',
        'mother_dead',
        'father_nid',
        'father_dob',
        'father_name_bn',
        'father_name_en',
        'father_mobile',
        'father_occupation',
        'father_dead',
        'present_address_division',
        'present_address_district',
        'present_address_upazila',
        'present_address_city',
        'present_address_ward',
        'present_address_village',
        'present_address_house_number',
        'present_address_post',
        'present_address_post_code',
        'permanent_address_division',
        'permanent_address_district',
        'permanent_address_upazila',
        'permanent_address_city',
        'permanent_address_ward',
        'permanent_address_village',
        'permanent_address_house_number',
        'permanent_address_post',
        'permanent_address_post_code',
        'information_correct',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function parent()
    {
        return $this->belongsTo(ParentModel::class);
    }


}
