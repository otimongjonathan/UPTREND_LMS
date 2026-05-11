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
        Schema::create('loan_products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->decimal('min_amount', 15, 2);
            $table->decimal('max_amount', 15, 2);
            $table->integer('min_term');
            $table->integer('max_term');
            $table->decimal('interest_rate', 5, 2);
            $table->decimal('processing_fee_percent', 5, 2)->default(0);
            $table->decimal('late_payment_fee_percent', 5, 2)->default(0);
            $table->decimal('insurance_premium_percent', 5, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('requirements')->nullable();
            $table->json('features')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_products');
    }
};
