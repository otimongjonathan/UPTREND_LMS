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
        Schema::create('repayment_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_disbursement_id')->constrained('loan_disbursements')->cascadeOnDelete();
            $table->foreignId('loan_application_id')->constrained('loan_applications')->cascadeOnDelete();
            
            // Schedule Details
            $table->integer('installment_number');
            $table->date('due_date');
            $table->decimal('principal_amount', 15, 2);
            $table->decimal('interest_amount', 15, 2);
            $table->decimal('total_amount', 15, 2);
            $table->decimal('remaining_balance', 15, 2)->nullable();
            
            // Payment Info
            $table->date('paid_date')->nullable();
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->enum('status', [
                'pending',
                'paid',
                'overdue',
                'partially_paid',
                'defaulted'
            ])->default('pending');
            
            // Payment Method
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            
            // Days Tracking
            $table->integer('days_overdue')->default(0);
            $table->date('original_due_date')->nullable();
            
            // Notes
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['loan_disbursement_id', 'due_date']);
            $table->index(['status', 'due_date']);
            $table->index(['loan_application_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repayment_schedules');
    }
};
