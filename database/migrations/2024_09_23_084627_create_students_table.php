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
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('class_id')->constrained('school_classes'); // Reference school_classes
            $table->foreignId('section_id')->constrained('sections');
            $table->foreignId('parent_id')->constrained('parent_models');
            $table->string('roll_number')->unique();
            $table->date('dob');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('address')->nullable();
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
