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
        Schema::create('patients', function (Blueprint $table) {
            $table->id('patient_id');
            $table->string('philhealth_id', 20)->nullable();
            $table->string('first_name', 50);
            $table->string('middle_name', 50)->nullable();
            $table->string('last_name', 50);
            $table->enum('suffix', ['None', 'Jr', 'Sr', 'I', 'II', 'III', 'IV'])->default('None');
            $table->date('date_of_birth');
            $table->enum('gender', ['Male', 'Female']);
            
            // Parental Details (for minors)
            $table->string('fathers_first_name', 50)->nullable();
            $table->string('fathers_middle_name', 50)->nullable();
            $table->string('fathers_last_name', 50)->nullable();
            $table->enum('fathers_suffix', ['None', 'Jr', 'Sr', 'I', 'II', 'III', 'IV'])->default('None');
            
            $table->string('mothers_first_name', 50)->nullable();
            $table->string('mothers_middle_name', 50)->nullable();
            $table->string('mothers_last_name', 50)->nullable();
            $table->enum('mothers_suffix', ['None', 'Jr', 'Sr', 'I', 'II', 'III', 'IV'])->default('None');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
