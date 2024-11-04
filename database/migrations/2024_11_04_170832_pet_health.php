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
        Schema::create('pet_allergies', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
        Schema::create('pet_health_history', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->
            $table->date('date_added'); // planning to have the client provide get the timestamp
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
