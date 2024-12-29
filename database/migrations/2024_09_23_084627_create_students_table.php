<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('school_classes');
            $table->foreignId('section_id')->constrained('sections');
            $table->foreignId('parent_id')->nullable()->constrained('parent_models');

            $table->string('photo')->nullable();
            $table->string('student_id')->unique();
            $table->string('form_number')->unique()->nullable();
            $table->string('name_bn')->nullable();
            $table->string('name_en');
            $table->string('birth_certificate_number')->nullable();
            $table->string('birth_place_district');
            $table->date('date_of_birth');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('nationality');
            $table->string('religion');
            $table->string('blood_group')->nullable();
            $table->string('class_role');
            $table->boolean('minorities')->default(false);
            $table->string('minority_name')->nullable();
            $table->string('handicap')->nullable();

            // Mother details
            $table->string('mother_nid')->nullable();
            $table->date('mother_dob')->nullable();
            $table->string('mother_name_bn')->nullable();
            $table->string('mother_name_en')->nullable();
            $table->string('mother_mobile')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->boolean('mother_dead')->default(false);

            // Father details
            $table->string('father_nid')->nullable();
            $table->date('father_dob')->nullable();
            $table->string('father_name_bn')->nullable();
            $table->string('father_name_en')->nullable();
            $table->string('father_mobile')->nullable();
            $table->string('father_occupation')->nullable();
            $table->boolean('father_dead')->default(false);

            // Present address
            $table->string('present_address_division')->nullable();
            $table->string('present_address_district')->nullable();
            $table->string('present_address_upazila')->nullable();
            $table->string('present_address_city')->nullable();
            $table->string('present_address_ward')->nullable();
            $table->string('present_address_village')->nullable();
            $table->string('present_address_house_number')->nullable();
            $table->string('present_address_post')->nullable();
            $table->string('present_address_post_code')->nullable();

            // Permanent address
            $table->string('permanent_address_division')->nullable();
            $table->string('permanent_address_district')->nullable();
            $table->string('permanent_address_upazila')->nullable();
            $table->string('permanent_address_city')->nullable();
            $table->string('permanent_address_ward')->nullable();
            $table->string('permanent_address_village')->nullable();
            $table->string('permanent_address_house_number')->nullable();
            $table->string('permanent_address_post')->nullable();
            $table->string('permanent_address_post_code')->nullable();


            $table->boolean('information_correct')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
