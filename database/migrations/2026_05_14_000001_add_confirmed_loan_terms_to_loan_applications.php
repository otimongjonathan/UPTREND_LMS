<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            // Confirmed loan terms (set by staff during approval)
            $table->string('confirmed_payment_frequency')->after('repayment_schedule')->nullable();
            $table->date('confirmed_first_due_date')->after('confirmed_payment_frequency')->nullable();
            $table->integer('confirmed_term_months')->after('confirmed_first_due_date')->nullable();
            $table->integer('confirmed_installment_count')->after('confirmed_term_months')->nullable();
            $table->decimal('confirmed_installment_amount', 15, 2)->after('confirmed_installment_count')->nullable();
            $table->date('confirmed_final_due_date')->after('confirmed_installment_amount')->nullable();
            
            // Track when terms were last modified
            $table->timestamp('terms_last_modified')->after('confirmed_final_due_date')->nullable();
            $table->unsignedBigInteger('terms_modified_by')->after('terms_last_modified')->nullable();
            
            // Add indexes
            $table->index('confirmed_payment_frequency');
            $table->index('confirmed_first_due_date');
            $table->foreign('terms_modified_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->dropForeign(['terms_modified_by']);
            $table->dropIndex(['confirmed_payment_frequency']);
            $table->dropIndex(['confirmed_first_due_date']);
            
            $table->dropColumn([
                'confirmed_payment_frequency',
                'confirmed_first_due_date',
                'confirmed_term_months',
                'confirmed_installment_count',
                'confirmed_installment_amount',
                'confirmed_final_due_date',
                'terms_last_modified',
                'terms_modified_by'
            ]);
        });
    }
};