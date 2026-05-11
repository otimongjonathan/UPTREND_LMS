<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->foreignId('loan_product_id')->nullable()->after('user_id')->constrained('loan_products')->onDelete('set null');
            $table->index('loan_product_id');
        });
    }

    public function down()
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->dropForeign(['loan_product_id']);
            $table->dropIndex(['loan_product_id']);
            $table->dropColumn('loan_product_id');
        });
    }
};