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
            $table->decimal('processing_fee_percent', 5, 2)->default(0)->after('disbursement_amount');
            $table->decimal('processing_fee_amount', 15, 2)->default(0)->after('processing_fee_percent');
            $table->decimal('insurance_premium_percent', 5, 2)->default(0)->after('processing_fee_amount');
            $table->decimal('insurance_premium_amount', 15, 2)->default(0)->after('insurance_premium_percent');
            $table->decimal('tax_percent', 5, 2)->default(0)->after('insurance_premium_amount');
            $table->decimal('tax_amount', 15, 2)->default(0)->after('tax_percent');
            $table->decimal('total_deductions', 15, 2)->default(0)->after('tax_amount');
            $table->decimal('net_disbursement_amount', 15, 2)->default(0)->after('total_deductions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_disbursements', function (Blueprint $table) {
            $table->dropColumn([
                'processing_fee_percent',
                'processing_fee_amount',
                'insurance_premium_percent',
                'insurance_premium_amount',
                'tax_percent',
                'tax_amount',
                'total_deductions',
                'net_disbursement_amount'
            ]);
        });
    }
};
