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
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('profile_image')->nullable()->after('salary_amount');
            $table->text('address')->nullable()->after('profile_image');
            $table->string('phone_number')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn('profile_image');
            $table->dropColumn('address');
            $table->dropColumn('phone_number');
        });
    }
};
