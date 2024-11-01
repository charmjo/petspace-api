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
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_owner_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('breed');
            $table->string('animal_type');
            $table->string('bio')->nullable();
            $table->timestamp('dob')->nullable();
            $table->string('color')->nullable();
            $table->string('gender')->nullable(); // I'd rather have the front end deal with this
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
