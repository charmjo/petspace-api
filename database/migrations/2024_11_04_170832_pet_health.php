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
        // dictionary schema
        Schema::create('pet_allergens', function (Blueprint $table) {
            $table->id();
            $table->string('allergen');
            $table->string('classification');
            $table->string('species_affected');
            $table->timestamps();
        });
        // pet allergy (temporary table to hold current allergy)
        Schema::create('pet_allergy_record', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained('pets')->onDelete('cascade');
            $table->foreignId('allergen_id')->constrained('pet_allergens')->onDelete('cascade');
            $table->foreignId('added_by')->constrained('users')->onDelete('cascade');
            $table->unique(['pet_id','allergen_id', 'added_by']);
            $table->timestamps();
        });
        // this acts as a screenshot of the pet allergy as the pet_allergies is constantly updated.
        Schema::create('pet_allergy_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('allergen_id')->constrained('pet_allergens')->onDelete('cascade');
            $table->foreignId('pet_id')->constrained('pets')->onDelete('cascade');
            $table->foreignId('added_by')->constrained('users')->onDelete('cascade');
            $table->timestamps(); // planning to have the client provide get the timestamp
        });
        Schema::create('pet_weight_record', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained('pets')->onDelete('cascade');
            $table->foreignId('added_by')->constrained('users')->onDelete('cascade');
            $table->decimal('weight');
            $table->timestamps(); // planning to have the client provide get the timestamp
        });
        Schema::create('pet_special_conditions_record', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained('pets')->onDelete('cascade');
            $table->foreignId('added_by')->constrained('users')->onDelete('cascade');
            $table->string('condition_name');
            $table->string('condition_note');
            $table->timestamps(); // planning to have the client provide get the timestamp
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pet_allergy_record');
        Schema::dropIfExists('pet_allergens');
        Schema::dropIfExists('pet_allergy_history');
        Schema::dropIfExists('pet_weight_record');
        Schema::dropIfExists('pet_special_conditions_record');
    }
};
