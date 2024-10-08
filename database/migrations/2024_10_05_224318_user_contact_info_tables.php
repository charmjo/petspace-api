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
        // create address table, only 1 contact number per user. I won't make
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('street_name');
            $table->string('city');
            $table->string('province');
        });
         // create contact info table
         Schema::create('user_phone_number', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('country_code');
            $table->string('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
