<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement(
            "UPDATE users SET password = ? WHERE email = 'test@example.com'",
            [Hash::make('123456')]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement(
            "UPDATE users SET password = ? WHERE email = 'test@example.com'",
            [Hash::make('password')]
        );
    }
};
