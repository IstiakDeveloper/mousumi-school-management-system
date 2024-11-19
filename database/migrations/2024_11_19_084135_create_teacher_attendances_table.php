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
        Schema::create('teacher_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->date('date');
            $table->datetime('first_attendance')->nullable(); // First punch of the day
            $table->datetime('last_attendance')->nullable();  // Last punch of the day
            $table->integer('total_punches')->default(0);    // Total number of punches in the day
            $table->string('status')->default('present');    // present, late, absent
            // Store all attendance logs for the day
            $table->json('attendance_logs')->nullable();     // Store all punch records
            $table->timestamps();

            // Compound unique index to prevent duplicate entries for same teacher and date
            $table->unique(['teacher_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_attendances');
    }
};
