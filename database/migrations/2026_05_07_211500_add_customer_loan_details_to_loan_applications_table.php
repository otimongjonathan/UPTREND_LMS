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
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->string('applicant_full_name')->nullable()->after('user_id');
            $table->date('dob')->nullable()->after('applicant_full_name');
            $table->timestamp('application_date')->nullable()->after('dob');
            $table->string('district_city')->nullable()->after('application_date');
            $table->string('county')->nullable()->after('district_city');
            $table->string('sub_county')->nullable()->after('county');
            $table->string('parish')->nullable()->after('sub_county');
            $table->string('village')->nullable()->after('parish');
            $table->string('residence_status')->nullable()->after('village');
            $table->string('po_box')->nullable()->after('residence_status');
            $table->string('gender')->nullable()->after('po_box');
            $table->string('marital_status')->nullable()->after('gender');
            $table->string('loan_type')->nullable()->after('marital_status');
            $table->string('repayment_schedule')->nullable()->after('loan_type');
            $table->string('police_letter_path')->nullable()->after('repayment_schedule');
            $table->string('financial_statement_path')->nullable()->after('police_letter_path');
            $table->string('national_id_path')->nullable()->after('financial_statement_path');
            $table->string('loan_guarantee_one_path')->nullable()->after('national_id_path');
            $table->string('loan_guarantee_two_path')->nullable()->after('loan_guarantee_one_path');
            $table->string('proof_of_residence_path')->nullable()->after('loan_guarantee_two_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->dropColumn([
                'applicant_full_name',
                'dob',
                'application_date',
                'district_city',
                'county',
                'sub_county',
                'parish',
                'village',
                'residence_status',
                'po_box',
                'gender',
                'marital_status',
                'loan_type',
                'repayment_schedule',
                'police_letter_path',
                'financial_statement_path',
                'national_id_path',
                'loan_guarantee_one_path',
                'loan_guarantee_two_path',
                'proof_of_residence_path',
            ]);
        });
    }
};

