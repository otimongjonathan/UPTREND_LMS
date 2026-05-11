<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Collateral extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_application_id',
        'collateral_type',
        'description',
        'estimated_value',
        'collateral_document_path',
        'valuation_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'estimated_value' => 'decimal:2',
        'valuation_date' => 'date',
    ];

    public function loanApplication(): BelongsTo
    {
        return $this->belongsTo(LoanApplication::class);
    }
}
