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
        Schema::create('repayment_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repayment_schedule_id')->constrained('repayment_schedules')->cascadeOnDelete();
            $table->foreignId('loan_application_id')->constrained('loan_applications')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            
            $table->integer('days_before_due')->default(0);
            $table->boolean('reminded')->default(false);
            $table->timestamp('reminder_sent_at')->nullable();
            $table->string('reminder_type')->default('email'); // email, sms, both
            $table->text('reminder_message')->nullable();
            
            $table->timestamps();
            
            $table->index(['repayment_schedule_id', 'reminded']);
            $table->index(['user_id', 'reminded']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repayment_reminders');
    }
};
