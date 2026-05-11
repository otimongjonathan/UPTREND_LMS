<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('repayments', function (Blueprint $table) {
            // Add installment tracking fields
            $table->integer('installment_number')->after('loan_application_id')->default(1);
            $table->decimal('principal_amount', 15, 2)->after('amount')->default(0);
            $table->decimal('interest_amount', 15, 2)->after('principal_amount')->default(0);
            $table->decimal('late_fee', 10, 2)->after('interest_amount')->default(0);
            $table->decimal('remaining_balance', 15, 2)->after('late_fee')->default(0);
            
            // Add payment tracking
            $table->string('payment_frequency')->after('remaining_balance')->nullable(); // weekly, monthly, etc.
            $table->date('original_due_date')->after('due_date')->nullable();
            $table->integer('days_overdue')->after('original_due_date')->default(0);
            
            // Add indexes for performance
            $table->index(['loan_application_id', 'installment_number']);
            $table->index(['status', 'due_date']);
            $table->index('days_overdue');
        });
    }

    public function down()
    {
        Schema::table('repayments', function (Blueprint $table) {
            $table->dropIndex(['loan_application_id', 'installment_number']);
            $table->dropIndex(['status', 'due_date']);
            $table->dropIndex(['days_overdue']);
            
            $table->dropColumn([
                'installment_number',
                'principal_amount', 
                'interest_amount',
                'late_fee',
                'remaining_balance',
                'payment_frequency',
                'original_due_date',
                'days_overdue'
            ]);
        });
    }
};