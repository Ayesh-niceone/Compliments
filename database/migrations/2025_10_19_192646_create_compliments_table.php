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
            $table->unsignedBigInteger('care_user_id')->nullable();
            $table->string('customer_name');
            $table->string('phone');
            $table->string('plate_number');
            $table->timestamp('closed_at')->nullable();
            $table->unsignedBigInteger('department_id');
            $table->text('comment');
            $table->text('care_comment')->nullable();
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('completion_type_id');
            $table->enum('target_type', ['customer', 'worker']);
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
