<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loan_disbursements', function (Blueprint $table) {
            $table->integer('grace_period_months')->default(2)->after('first_payment_date');
            $table->date('grace_period_end_date')->nullable()->after('grace_period_months');
        });
    }

    public function down(): void
    {
        Schema::table('loan_disbursements', function (Blueprint $table) {
            $table->dropColumn(['grace_period_months', 'grace_period_end_date']);
        });
    }
};
