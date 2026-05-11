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
        Schema::create('repayments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_application_id')->constrained('loan_applications')->onDelete('cascade');
            $table->decimal('amount', 12, 2)->comment('Repayment amount');
            $table->date('due_date')->comment('When payment is due');
            $table->date('paid_date')->nullable()->comment('When payment was made');
            $table->decimal('paid_amount', 12, 2)->nullable()->comment('Actual amount paid');
            $table->enum('status', ['pending', 'partial', 'completed'])->default('pending');
            $table->string('payment_method')->nullable()->comment('Bank transfer, mobile money, cash, etc.');
            $table->string('payment_reference')->nullable()->comment('Transaction ID or receipt number');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repayments');
    }
};
