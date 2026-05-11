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
        Schema::create('loan_disbursements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_application_id')->constrained()->cascadeOnDelete();
            $table->decimal('disbursement_amount', 15, 2);
            $table->date('disbursement_date');
            $table->string('disbursement_method');
            $table->string('bank_account')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('disbursement_document_path')->nullable();
            $table->enum('status', ['pending', 'approved', 'disbursed', 'cancelled'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->datetime('approved_at')->nullable();
            $table->foreignId('disbursed_by')->nullable()->constrained('users');
            $table->datetime('disbursed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_disbursements');
    }
};
