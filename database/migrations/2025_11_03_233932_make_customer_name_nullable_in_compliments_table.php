<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('compliments', function (Blueprint $table) {
            $table->string('customer_name')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('plate_number')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('compliments', function (Blueprint $table) {
            $table->string('customer_name')->nullable(false)->change();
            $table->string('phone')->nullable(false)->change();
            $table->string('plate_number')->nullable(false)->change();
        });
    }
};
