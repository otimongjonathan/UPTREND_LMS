<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'loan_product_id',
        'applicant_full_name',
        'dob',
        'application_date',
        'district_city',
        'county',
        'sub_county',
        'parish',
        'village',
        'residence_status',
        'po_box',
        'gender',
        'marital_status',
        'employment_status',
        'occupation',
        'loan_type',
        'repayment_schedule',
        'confirmed_payment_frequency',
        'confirmed_first_due_date',
        'confirmed_term_months',
        'confirmed_installment_count',
        'confirmed_installment_amount',
        'confirmed_final_due_date',
        'terms_last_modified',
        'terms_modified_by',
        'processing_fee_percent',
        'processing_fee_amount',
        'insurance_fee_percent',
        'insurance_fee_amount',
        'vat_percent',
        'vat_amount',
        'withholding_tax_percent',
        'withholding_tax_amount',
        'total_fees',
        'total_taxes',
        'net_disbursement_amount',
        'gross_repayment_amount',
        'fees_calculated',
        'fees_calculated_at',
        'fees_calculated_by',
        'police_letter_path',
        'financial_statement_path',
        'national_id_path',
        'loan_guarantee_one_path',
        'loan_guarantee_two_path',
        'proof_of_residence_path',
        'amount',
        'term_months',
        'purpose',
        'monthly_income',
        'status',
        'notes',
    ];

    protected $casts = [
        'dob' => 'date',
        'application_date' => 'datetime',
        'confirmed_first_due_date' => 'date',
        'confirmed_final_due_date' => 'date',
        'terms_last_modified' => 'datetime',
        'fees_calculated_at' => 'datetime',
        'fees_calculated' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function repayments()
    {
        return $this->hasMany(Repayment::class);
    }

    public function guarantors()
    {
        return $this->hasMany(LoanGuarantor::class);
    }

    public function collaterals()
    {
        return $this->hasMany(Collateral::class);
    }

    public function disbursements()
    {
        return $this->hasMany(LoanDisbursement::class);
    }

    public function repaymentSchedules()
    {
        return $this->hasMany(RepaymentSchedule::class);
    }

    public function loanRepaymentSchedule()
    {
        return $this->hasOne(LoanRepaymentSchedule::class);
    }

    public function product()
    {
        return $this->belongsTo(LoanProduct::class, 'loan_product_id');
    }

    public function loanProduct()
    {
        return $this->belongsTo(LoanProduct::class, 'loan_product_id');
    }

    /**
     * Sync loan details with repayment data
     */
    public function syncWithRepayments()
    {
        return \App\Services\LoanRepaymentSyncService::syncLoanWithRepayments($this);
    }

    /**
     * Create repayment schedule
     */
    public function createRepaymentSchedule()
    {
        return \App\Services\LoanRepaymentSyncService::createRepaymentSchedule($this);
    }

    /**
     * Sync repayments with product details
     */
    public function syncWithProduct()
    {
        return \App\Services\LoanProductRepaymentSyncService::syncRepaymentWithProduct($this);
    }

    /**
     * Get product summary
     */
    public function getProductSummary()
    {
        return \App\Services\LoanProductRepaymentSyncService::getLoanProductSummary($this);
    }

    /**
     * Get total amount paid
     */
    public function getTotalPaidAttribute()
    {
        return $this->repayments->sum('paid_amount') ?? 0;
    }

    /**
     * Get outstanding balance
     */
    public function getOutstandingBalanceAttribute()
    {
        $totalRepayable = $this->repayments->sum('amount') + $this->repayments->sum('late_fee');
        return max(0, $totalRepayable - $this->getTotalPaidAttribute());
    }

    /**
     * Get payment progress percentage
     */
    public function getPaymentProgressAttribute()
    {
        $totalRepayable = $this->repayments->sum('amount');
        return $totalRepayable > 0 ? round(($this->getTotalPaidAttribute() / $totalRepayable) * 100, 2) : 0;
    }

    /**
     * Check if loan is overdue
     */
    public function isOverdue()
    {
        return $this->repayments()->where('status', 'pending')
            ->where('due_date', '<', now())
            ->exists();
    }

    /**
     * Get next payment due
     */
    public function getNextPayment()
    {
        return $this->repayments()->where('status', 'pending')
            ->orderBy('due_date')
            ->first();
    }

    /**
     * Get overdue payments
     */
    public function getOverduePayments()
    {
        return $this->repayments()->where('status', 'pending')
            ->where('due_date', '<', now())
            ->orderBy('due_date')
            ->get();
    }
    
    /**
     * Update confirmed loan terms
     */
    public function updateConfirmedTerms($frequency, $firstDueDate, $modifiedBy)
    {
        return \App\Services\LoanTermSyncService::regenerateTerms($this, $frequency, $firstDueDate);
    }
    
    /**
     * Get confirmed terms or fallback to original terms
     */
    public function getEffectiveTerms()
    {
        return [
            'payment_frequency' => $this->confirmed_payment_frequency ?? $this->repayment_schedule,
            'term_months' => $this->confirmed_term_months ?? $this->term_months,
            'installment_count' => $this->confirmed_installment_count,
            'installment_amount' => $this->confirmed_installment_amount,
            'first_due_date' => $this->confirmed_first_due_date,
            'final_due_date' => $this->confirmed_final_due_date,
        ];
    }
    
    /**
     * Check if terms have been confirmed by staff
     */
    public function hasConfirmedTerms()
    {
        return !is_null($this->confirmed_payment_frequency) && !is_null($this->confirmed_first_due_date);
    }
    
    /**
     * Get fee breakdown
     */
    public function getFeeBreakdown()
    {
        return \App\Services\LoanFeeCalculationService::getFeeBreakdown($this);
    }
    
    /**
     * Calculate and apply fees
     */
    public function calculateFees(array $feeSettings = [])
    {
        return \App\Services\LoanFeeCalculationService::applyFeesToLoan($this, $feeSettings);
    }
    
    /**
     * Check if fees have been calculated
     */
    public function hasCalculatedFees()
    {
        return $this->fees_calculated;
    }
}

