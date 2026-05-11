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
        Schema::table('loan_disbursements', function (Blueprint $table) {
            // Repayment Schedule Configuration
            $table->enum('payment_frequency', [
                'daily',
                'weekly',
                'bi-weekly',
                'monthly',
                'bi-monthly',
                'quarterly',
                'semi-annual',
                'annual'
            ])->after('notes')->nullable();
            
            $table->integer('number_of_installments')->after('payment_frequency')->nullable();
            $table->date('first_payment_date')->after('number_of_installments')->nullable();
            
            // Transaction Details
            $table->string('transaction_id')->after('first_payment_date')->nullable();
            $table->enum('transaction_status', [
                'pending',
                'processing',
                'completed',
                'failed',
                'cancelled'
            ])->after('transaction_id')->default('pending');
            
            // Bank Details (enhanced from existing bank_account)
            $table->string('bank_name')->after('transaction_status')->nullable();
            $table->string('account_holder_name')->after('bank_name')->nullable();
            $table->string('account_number')->after('account_holder_name')->nullable();
            $table->string('routing_number')->after('account_number')->nullable();
            
            // Cash Handling
            $table->string('cash_received_by')->after('routing_number')->nullable();
            $table->text('cash_notes')->after('cash_received_by')->nullable();
            
            // Additional Transaction Info
            $table->text('transaction_notes')->after('cash_notes')->nullable();
            $table->timestamp('transaction_recorded_at')->after('transaction_notes')->nullable();
            
            // Audit Trail
            $table->foreignId('verified_by')->after('transaction_recorded_at')->nullable()->constrained('users');
            $table->timestamp('verified_at')->after('verified_by')->nullable();
            
            // Indexes for performance
            $table->index('transaction_id');
            $table->index('transaction_status');
            $table->index('payment_frequency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_disbursements', function (Blueprint $table) {
            $table->dropIndex(['transaction_id']);
            $table->dropIndex(['transaction_status']);
            $table->dropIndex(['payment_frequency']);
            $table->dropForeignIdFor(\App\Models\User::class, 'verified_by');
            
            $table->dropColumn([
                'payment_frequency',
                'number_of_installments',
                'first_payment_date',
                'transaction_id',
                'transaction_status',
                'bank_name',
                'account_holder_name',
                'account_number',
                'routing_number',
                'cash_received_by',
                'cash_notes',
                'transaction_notes',
                'transaction_recorded_at',
                'verified_by',
                'verified_at',
            ]);
        });
    }
};
