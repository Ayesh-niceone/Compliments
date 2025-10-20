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
        Schema::create('compliments', function (Blueprint $table) {
            $table->id();
            $table->enum('target_type', ['customer', 'worker']);
            $table->unsignedBigInteger('target_id');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('care_user_id')->nullable();
            $table->text('comment')->nullable();
            $table->enum('status', ['new', 'in_progress', 'resolved', 'closed'])->default('new');
            $table->string('name');
            $table->string('phone');
            $table->string('email')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compliments');
    }
};
