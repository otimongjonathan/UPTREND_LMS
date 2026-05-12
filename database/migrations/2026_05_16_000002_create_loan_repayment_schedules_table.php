<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_repayment_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_application_id')->unique()->constrained('loan_applications')->cascadeOnDelete();
            $table->foreignId('loan_disbursement_id')->nullable()->constrained('loan_disbursements')->cascadeOnDelete();
            
            // Schedule Configuration
            $table->integer('total_installments');
            $table->string('payment_frequency'); // weekly, bi-weekly, monthly, quarterly
            $table->decimal('installment_amount', 15, 2);
            $table->decimal('total_loan_amount', 15, 2);
            $table->decimal('total_interest', 15, 2);
            $table->decimal('total_repayable', 15, 2);
            
            // Grace Period
            $table->integer('grace_period_months')->default(2);
            $table->date('grace_period_end_date');
            $table->date('first_payment_date');
            $table->date('final_payment_date');
            
            // Tracking
            $table->json('installments'); // Array of all installments with payment tracking
            $table->integer('installments_paid')->default(0);
            $table->integer('installments_pending')->default(0);
            $table->integer('installments_overdue')->default(0);
            $table->decimal('total_paid', 15, 2)->default(0);
            $table->decimal('total_outstanding', 15, 2)->default(0);
            
            // Status
            $table->enum('status', ['active', 'completed', 'defaulted', 'restructured'])->default('active');
            $table->date('completed_at')->nullable();
            
            $table->timestamps();
            
            $table->index(['loan_application_id', 'status']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_repayment_schedules');
    }
};
