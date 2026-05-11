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
        // Enhance payment_receipts to link to repayment schedules
        Schema::table('payment_receipts', function (Blueprint $table) {
            $table->foreignId('repayment_schedule_id')->after('loan_application_id')->nullable()->constrained('repayment_schedules')->cascadeOnDelete();
            $table->decimal('principal_paid', 15, 2)->after('amount_paid')->default(0);
            $table->decimal('interest_paid', 15, 2)->after('principal_paid')->default(0);
            $table->decimal('penalty_paid', 15, 2)->after('interest_paid')->default(0);
            
            $table->index('repayment_schedule_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_receipts', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\RepaymentSchedule::class);
            $table->dropIndex(['repayment_schedule_id']);
            $table->dropColumn([
                'repayment_schedule_id',
                'principal_paid',
                'interest_paid',
                'penalty_paid',
            ]);
        });
    }
};
