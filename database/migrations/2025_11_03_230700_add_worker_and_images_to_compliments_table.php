<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('compliments', function (Blueprint $table) {
            $table->foreignId('worker_id')->nullable()->constrained()->onDelete('set null');
            $table->json('images')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('compliments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('worker_id');
            $table->dropColumn('images');
        });
    }
};

