<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->enum('interest_calculation_method', ['simple', 'compound'])
                  ->default('compound')
                  ->after('applied_interest_rate')
                  ->comment('Method used to calculate interest');
        });
    }

    public function down()
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->dropColumn('interest_calculation_method');
        });
    }
};