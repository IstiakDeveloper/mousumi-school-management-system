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
        Schema::create('grade_books', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('exam_category_id')->constrained('exam_categories')->onDelete('cascade');
            $table->foreignId('school_class_id')->constrained('school_classes')->onDelete('cascade');
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');

            // Marks and grades
            $table->integer('mark')->unsigned();
            $table->foreignId('grade_id')->nullable()->constrained('grades')->onDelete('set null'); // Link to Grade based on marks

            // Audit columns
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_books');
    }
};
