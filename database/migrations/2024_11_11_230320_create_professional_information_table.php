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
        Schema::create('professional_information', function (Blueprint $table) {
            $table->id();

            $table->string('license_number')->unique(); // Veterinarian's license number
            $table->string('license_province'); // Province or territory where licensed
            $table->enum('license_type', ['Full', 'Provisional', 'Restricted']); // License type
            $table->string('specialty')->nullable(); // Veterinarian's specialty
            $table->text('board_certifications')->nullable(); // Board certifications (as text)
            $table->year('graduation_year'); // Year of graduation
            $table->string('veterinary_school'); // Institution from which the veterinarian graduated
            $table->integer('years_of_experience'); // Number of years of practice
            $table->text('continuing_education')->nullable(); // List of certifications or courses
            $table->string('professional_title')->nullable(); // Professional title (e.g., Dr., DVM)
            $table->boolean('is_verified');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key with cascade delete
            $table->timestamps(); // To store created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professional_information');
    }
};
