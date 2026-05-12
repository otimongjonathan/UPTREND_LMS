<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loan_repayment_schedules', function (Blueprint $table) {
            $table->decimal('total_fees', 15, 2)->default(0)->after('total_interest');
            $table->decimal('total_taxes', 15, 2)->default(0)->after('total_fees');
        });
    }

    public function down(): void
    {
        Schema::table('loan_repayment_schedules', function (Blueprint $table) {
            $table->dropColumn(['total_fees', 'total_taxes']);
        });
    }
};
