<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'provider_company',
        'name',
        'description',
        'min_amount',
        'max_amount',
        'min_term',
        'max_term',
        'interest_rate',
        'processing_fee_percent',
        'late_payment_fee_percent',
        'insurance_premium_percent',
        'is_active',
        'requirements',
        'features',
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'processing_fee_percent' => 'decimal:2',
        'late_payment_fee_percent' => 'decimal:2',
        'insurance_premium_percent' => 'decimal:2',
        'is_active' => 'boolean',
        'requirements' => 'json',
        'features' => 'json',
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function loanApplications(): HasMany
    {
        return $this->hasMany(LoanApplication::class);
    }
}
