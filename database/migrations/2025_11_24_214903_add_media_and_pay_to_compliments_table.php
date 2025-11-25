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
        Schema::table('compliments', function (Blueprint $table) {
            $table->json('video')->nullable();
            $table->json('audio')->nullable();
            $table->string('missed_pay')->nullable();
            $table->string('paid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('compliments', function (Blueprint $table) {
                    $table->dropColumn(['video', 'audio', 'missed_pay', 'paid']);
        });
    }
};
