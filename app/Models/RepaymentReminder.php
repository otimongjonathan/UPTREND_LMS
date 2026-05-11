<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RepaymentReminder extends Model
{
    protected $fillable = [
        'repayment_schedule_id',
        'loan_application_id',
        'user_id',
        'days_before_due',
        'reminded',
        'reminder_sent_at',
        'reminder_type',
        'reminder_message',
    ];

    protected $casts = [
        'reminded' => 'boolean',
        'reminder_sent_at' => 'datetime',
    ];

    public function repaymentSchedule(): BelongsTo
    {
        return $this->belongsTo(RepaymentSchedule::class);
    }

    public function loanApplication(): BelongsTo
    {
        return $this->belongsTo(LoanApplication::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark reminder as sent
     */
    public function markAsSent(): void
    {
        $this->update([
            'reminded' => true,
            'reminder_sent_at' => now(),
        ]);
    }
}
