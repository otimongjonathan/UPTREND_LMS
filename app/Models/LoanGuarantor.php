<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanGuarantor extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_application_id',
        'full_name',
        'relationship',
        'contact_phone',
        'contact_email',
        'id_number',
        'address',
        'occupation',
        'monthly_income',
        'guarantor_document_path',
        'status',
        'notes',
    ];

    protected $casts = [
        'monthly_income' => 'decimal:2',
    ];

    public function loanApplication(): BelongsTo
    {
        return $this->belongsTo(LoanApplication::class);
    }
}
