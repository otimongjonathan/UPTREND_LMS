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
        Schema::table('payment_receipts', function (Blueprint $table) {
            if (!Schema::hasColumn('payment_receipts', 'loan_application_id')) {
                $table->foreignId('loan_application_id')->nullable()->constrained()->cascadeOnDelete();
            }
            if (!Schema::hasColumn('payment_receipts', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_receipts', function (Blueprint $table) {
            $table->dropForeignIdFor(LoanApplication::class);
            $table->dropForeignIdFor(User::class);
        });
    }
};
