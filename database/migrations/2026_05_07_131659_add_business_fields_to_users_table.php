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
        Schema::table('users', function (Blueprint $table) {
            $table->string('business_name')->after('name');
            $table->text('address')->after('business_name');
            $table->string('tel_no')->after('address');
            $table->string('financial_compliance_statement')->nullable()->after('tel_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['business_name', 'address', 'tel_no', 'financial_compliance_statement']);
        });
    }
};
