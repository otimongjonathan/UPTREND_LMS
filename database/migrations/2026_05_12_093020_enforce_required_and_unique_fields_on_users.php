<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Make fields required (NOT NULL)
            $table->string('business_name')->nullable(false)->change();
            $table->text('address')->nullable(false)->change();
            $table->string('tel_no')->nullable(false)->change();
            
            // Add unique constraints
            $table->unique('business_name');
            $table->unique('tel_no');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove unique constraints
            $table->dropUnique(['business_name']);
            $table->dropUnique(['tel_no']);
            
            // Make fields nullable again
            $table->string('business_name')->nullable()->change();
            $table->text('address')->nullable()->change();
            $table->string('tel_no')->nullable()->change();
        });
    }
};
