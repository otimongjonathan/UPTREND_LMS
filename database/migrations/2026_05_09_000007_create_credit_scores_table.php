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
        Schema::create('credit_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->integer('score')->default(0);
            $table->date('score_date');
            $table->integer('credit_history_months')->default(0);
            $table->integer('total_loans')->default(0);
            $table->integer('completed_loans')->default(0);
            $table->integer('defaulted_loans')->default(0);
            $table->decimal('default_rate', 5, 2)->default(0);
            $table->decimal('average_loan_amount', 15, 2)->default(0);
            $table->decimal('average_repayment_rate', 5, 2)->default(0);
            $table->decimal('on_time_payment_rate', 5, 2)->default(0);
            $table->enum('risk_level', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_scores');
    }
};
