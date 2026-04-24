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
            $table->id('schedule_id');

            // foreign key
            $table->foreignId('department_id')
                  ->constrained('departments', 'department_id')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->date('schedule_date');
            $table->integer('max_capacity');
            $table->integer('current_booked')->default(0);
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
