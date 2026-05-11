<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RepaymentSchedule extends Model
{
    protected $fillable = [
        'loan_disbursement_id',
        'loan_application_id',
        'installment_number',
        'due_date',
        'principal_amount',
        'interest_amount',
        'total_amount',
        'remaining_balance',
        'paid_date',
        'paid_amount',
        'status',
        'payment_method',
        'payment_reference',
        'days_overdue',
        'original_due_date',
        'notes',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_date' => 'date',
        'original_due_date' => 'date',
        'principal_amount' => 'decimal:2',
        'interest_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function loanDisbursement(): BelongsTo
    {
        return $this->belongsTo(LoanDisbursement::class);
    }

    public function loanApplication(): BelongsTo
    {
        return $this->belongsTo(LoanApplication::class);
    }

    public function paymentReceipts(): HasMany
    {
        return $this->hasMany(PaymentReceipt::class, 'repayment_schedule_id');
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(RepaymentReminder::class);
    }

    /**
     * Check if installment is overdue
     */
    public function isOverdue(): bool
    {
        return $this->status === 'overdue' || 
               ($this->status === 'pending' && $this->due_date < today());
    }

    /**
     * Check if installment is paid
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Check if installment is partially paid
     */
    public function isPartiallyCaught(): bool
    {
        return $this->status === 'partially_paid';
    }
}
