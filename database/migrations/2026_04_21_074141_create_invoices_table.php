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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id('invoice_id');
            
            // appointment_id must be unique to ensure 1 appointment = 1 invoice
            $table->foreignId('appointment_id')
                  ->unique() 
                  ->constrained('appointments', 'appointment_id')
                  ->onDelete('cascade');

            $table->decimal('total_amount', 10, 2)->default(0.00);
            $table->enum('payment_status', ['Unpaid', 'Paid', 'Partially Paid', 'Cancelled'])->default('Unpaid');
            $table->timestamp('issued_date')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
