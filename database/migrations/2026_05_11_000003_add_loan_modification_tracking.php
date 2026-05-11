<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            // Loan modification tracking
            $table->boolean('early_completion')->after('completion_date')->default(false);
            $table->decimal('interest_savings', 10, 2)->after('early_completion')->default(0);
            $table->decimal('credit_balance', 10, 2)->after('interest_savings')->default(0);
            
            // Restructuring tracking
            $table->date('restructuring_date')->after('credit_balance')->nullable();
            $table->integer('restructuring_count')->after('restructuring_date')->default(0);
            $table->decimal('restructuring_fee', 10, 2)->after('restructuring_count')->default(0);
            
            // Default tracking
            $table->date('default_date')->after('restructuring_fee')->nullable();
            $table->date('recovery_plan_date')->after('default_date')->nullable();
            $table->decimal('total_penalties', 10, 2)->after('recovery_plan_date')->default(0);
            
            // Adjustment history (JSON field)
            $table->json('adjustments')->after('total_penalties')->nullable();
            
            // Add indexes
            $table->index('early_completion');
            $table->index('restructuring_count');
            $table->index('default_date');
        });
        
        // Add status values for repayments
        Schema::table('repayments', function (Blueprint $table) {
            // Add new status options: restructured, defaulted, recalculated
            $table->string('modification_type')->after('status')->nullable();
            $table->json('modification_history')->after('modification_type')->nullable();
        });
    }

    public function down()
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->dropIndex(['early_completion']);
            $table->dropIndex(['restructuring_count']);
            $table->dropIndex(['default_date']);
            
            $table->dropColumn([
                'early_completion',
                'interest_savings',
                'credit_balance',
                'restructuring_date',
                'restructuring_count',
                'restructuring_fee',
                'default_date',
                'recovery_plan_date',
                'total_penalties',
                'adjustments'
            ]);
        });
        
        Schema::table('repayments', function (Blueprint $table) {
            $table->dropColumn(['modification_type', 'modification_history']);
        });
    }
};