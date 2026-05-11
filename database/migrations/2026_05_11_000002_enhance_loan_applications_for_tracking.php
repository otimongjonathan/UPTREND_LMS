<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            // Add loan lifecycle tracking
            $table->date('disbursement_date')->after('status')->nullable();
            $table->decimal('disbursed_amount', 15, 2)->after('disbursement_date')->default(0);
            $table->decimal('total_interest', 15, 2)->after('disbursed_amount')->default(0);
            $table->decimal('processing_fee', 10, 2)->after('total_interest')->default(0);
            $table->decimal('insurance_fee', 10, 2)->after('processing_fee')->default(0);
            $table->decimal('total_repayable', 15, 2)->after('insurance_fee')->default(0);
            
            // Add interest rate tracking (in case it differs from product rate)
            $table->decimal('applied_interest_rate', 5, 2)->after('total_repayable')->nullable();
            $table->integer('actual_term_months')->after('applied_interest_rate')->nullable();
            
            // Add completion tracking
            $table->date('completion_date')->after('actual_term_months')->nullable();
            $table->decimal('total_paid', 15, 2)->after('completion_date')->default(0);
            $table->decimal('outstanding_balance', 15, 2)->after('total_paid')->default(0);
            
            // Add indexes
            $table->index('disbursement_date');
            $table->index(['status', 'disbursement_date']);
            $table->index('outstanding_balance');
        });
    }

    public function down()
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->dropIndex(['disbursement_date']);
            $table->dropIndex(['status', 'disbursement_date']);
            $table->dropIndex(['outstanding_balance']);
            
            $table->dropColumn([
                'disbursement_date',
                'disbursed_amount',
                'total_interest',
                'processing_fee',
                'insurance_fee',
                'total_repayable',
                'applied_interest_rate',
                'actual_term_months',
                'completion_date',
                'total_paid',
                'outstanding_balance'
            ]);
        });
    }
};