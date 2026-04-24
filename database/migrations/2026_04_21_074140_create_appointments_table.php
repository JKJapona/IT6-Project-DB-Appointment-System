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
            $table->id('appointment_id');
            $table->string('reference_number', 50)->unique();
            
            // Foreign Keys
            $table->foreignId('patient_id')->constrained('patients', 'patient_id')->onDelete('cascade');
            $table->foreignId('schedule_id')->constrained('schedules', 'schedule_id')->onDelete('restrict');
            
            // Assigned Doctor (Points to staff table)
            $table->foreignId('assigned_doctor_id')->constrained('staff', 'staff_id')->onDelete('restrict');
            
            // Processed By (Nullable, points to staff table)
            $table->foreignId('processed_by_id')->nullable()->constrained('staff', 'staff_id')->onDelete('set null');

            $table->enum('status', ['Pending', 'Confirmed', 'Completed', 'Cancelled'])->default('Pending');
            $table->timestamp('booking_timestamp')->useCurrent();
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
