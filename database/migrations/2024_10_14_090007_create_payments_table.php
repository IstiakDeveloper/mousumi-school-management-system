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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->integer('year');
            $table->integer('month');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'paid', 'request_sent'])->default('pending');
            $table->enum('payment_method', ['cash', 'bank', 'online'])->nullable(); // Add payment method
            $table->string('receipt')->nullable(); // Add receipt (path to file)
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
