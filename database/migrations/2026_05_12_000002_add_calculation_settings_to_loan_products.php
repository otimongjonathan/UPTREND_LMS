<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('loan_products', function (Blueprint $table) {
            $table->enum('interest_calculation_method', ['simple', 'compound'])
                  ->default('compound')
                  ->after('interest_rate')
                  ->comment('Method used to calculate interest');
                  
            $table->enum('default_repayment_frequency', ['weekly', 'bi_weekly', 'monthly', 'quarterly'])
                  ->default('monthly')
                  ->after('interest_calculation_method')
                  ->comment('Default payment frequency for this product');
                  
            $table->boolean('allow_early_payment')
                  ->default(true)
                  ->after('default_repayment_frequency')
                  ->comment('Allow customers to pay early without penalty');
                  
            $table->decimal('early_payment_discount_percent', 5, 2)
                  ->default(0)
                  ->after('allow_early_payment')
                  ->comment('Discount percentage for early payments');
        });
    }

    public function down()
    {
        Schema::table('loan_products', function (Blueprint $table) {
            $table->dropColumn([
                'interest_calculation_method',
                'default_repayment_frequency', 
                'allow_early_payment',
                'early_payment_discount_percent'
            ]);
        });
    }
};