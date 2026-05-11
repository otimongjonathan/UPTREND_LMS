<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            // Fee structure
            $table->decimal('processing_fee_percent', 5, 2)->after('confirmed_final_due_date')->default(0);
            $table->decimal('processing_fee_amount', 15, 2)->after('processing_fee_percent')->default(0);
            $table->decimal('insurance_fee_percent', 5, 2)->after('processing_fee_amount')->default(0);
            $table->decimal('insurance_fee_amount', 15, 2)->after('insurance_fee_percent')->default(0);
            
            // Tax structure
            $table->decimal('vat_percent', 5, 2)->after('insurance_fee_amount')->default(18.00); // Uganda VAT
            $table->decimal('vat_amount', 15, 2)->after('vat_percent')->default(0);
            $table->decimal('withholding_tax_percent', 5, 2)->after('vat_amount')->default(0);
            $table->decimal('withholding_tax_amount', 15, 2)->after('withholding_tax_percent')->default(0);
            
            // Total calculations
            $table->decimal('total_fees', 15, 2)->after('withholding_tax_amount')->default(0);
            $table->decimal('total_taxes', 15, 2)->after('total_fees')->default(0);
            $table->decimal('net_disbursement_amount', 15, 2)->after('total_taxes')->default(0);
            $table->decimal('gross_repayment_amount', 15, 2)->after('net_disbursement_amount')->default(0);
            
            // Fee calculation settings
            $table->boolean('fees_calculated')->after('gross_repayment_amount')->default(false);
            $table->timestamp('fees_calculated_at')->after('fees_calculated')->nullable();
            $table->unsignedBigInteger('fees_calculated_by')->after('fees_calculated_at')->nullable();
            
            // Add indexes
            $table->index('fees_calculated');
            $table->foreign('fees_calculated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->dropForeign(['fees_calculated_by']);
            $table->dropIndex(['fees_calculated']);
            
            $table->dropColumn([
                'processing_fee_percent',
                'processing_fee_amount',
                'insurance_fee_percent', 
                'insurance_fee_amount',
                'vat_percent',
                'vat_amount',
                'withholding_tax_percent',
                'withholding_tax_amount',
                'total_fees',
                'total_taxes',
                'net_disbursement_amount',
                'gross_repayment_amount',
                'fees_calculated',
                'fees_calculated_at',
                'fees_calculated_by'
            ]);
        });
    }
};