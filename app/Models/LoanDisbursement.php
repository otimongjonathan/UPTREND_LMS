<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoanDisbursement extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_application_id',
        'disbursement_amount',
        'disbursement_date',
        'disbursement_method',
        'bank_account',
        'reference_number',
        'disbursement_document_path',
        'status',
        'approved_by',
        'approved_at',
        'disbursed_by',
        'disbursed_at',
        'notes',
        'payment_frequency',
        'number_of_installments',
        'first_payment_date',
        'transaction_id',
        'transaction_status',
        'bank_name',
        'account_holder_name',
        'account_number',
        'routing_number',
        'cash_received_by',
        'cash_notes',
        'transaction_notes',
        'transaction_recorded_at',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'disbursement_amount' => 'decimal:2',
        'disbursement_date' => 'date',
        'approved_at' => 'datetime',
        'disbursed_at' => 'datetime',
        'first_payment_date' => 'date',
        'transaction_recorded_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function loanApplication(): BelongsTo
    {
        return $this->belongsTo(LoanApplication::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function disburser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disbursed_by');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function repaymentSchedules(): HasMany
    {
        return $this->hasMany(RepaymentSchedule::class, 'loan_disbursement_id');
    }
}
