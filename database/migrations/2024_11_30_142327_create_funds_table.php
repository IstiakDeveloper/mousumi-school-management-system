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
        Schema::create('funds', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->enum('type', ['in', 'out']); // Fund In or Fund Out
            $table->decimal('amount', 15, 2);  // Amount of the transaction
            $table->text('description')->nullable(); // Optional description
            $table->decimal('balance', 15, 2);  // Current balance after the transaction
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funds');
    }
};
