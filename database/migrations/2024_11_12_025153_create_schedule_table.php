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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            // vet id
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('service_company_provider_name')->nullable();
            $table->date('schedule_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_booked')->default(false);
            $table->text('description')->nullable();
            $table->decimal('cost'); // I'd rather have the code format this.
            $table->foreignId('location')->nullable();
            $table->string('service_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
