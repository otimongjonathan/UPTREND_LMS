<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('business_name')->nullable()->change();
            $table->text('address')->nullable()->change();
            $table->string('tel_no')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('business_name')->nullable(false)->change();
            $table->text('address')->nullable(false)->change();
            $table->string('tel_no')->nullable(false)->change();
        });
    }
};
