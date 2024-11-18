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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            // Foreign key to the user who scheduled the appointment
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Foreign key to the pet involved in the appointment
            $table->foreignId('pet_id')->constrained('pets')->onDelete('cascade');

            // Foreign key to the service schedule this appointment is linked to
            $table->foreignId('schedule_id')->constrained('schedules')->onDelete('cascade');

            // Appointment related fields
            $table->enum('status', ['pending', 'confirmed', 'completed', 'canceled'])->default('pending');
            $table->timestamp('request_date')->nullable();
            $table->timestamp('request_updated_date')->nullable();

            // Optional: Tracks when the appointment was confirmed or canceled
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('canceled_at')->nullable();

            // Additional info about the appointment
            $table->text('notes')->nullable();

            // Timestamps for tracking creation and updates
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
