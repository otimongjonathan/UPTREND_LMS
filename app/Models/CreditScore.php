<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'score',
        'score_date',
        'credit_history_months',
        'total_loans',
        'completed_loans',
        'defaulted_loans',
        'default_rate',
        'average_loan_amount',
        'average_repayment_rate',
        'on_time_payment_rate',
        'risk_level',
        'remarks',
    ];

    protected $casts = [
        'score' => 'integer',
        'score_date' => 'date',
        'credit_history_months' => 'integer',
        'total_loans' => 'integer',
        'completed_loans' => 'integer',
        'defaulted_loans' => 'integer',
        'default_rate' => 'decimal:2',
        'average_loan_amount' => 'decimal:2',
        'average_repayment_rate' => 'decimal:2',
        'on_time_payment_rate' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
