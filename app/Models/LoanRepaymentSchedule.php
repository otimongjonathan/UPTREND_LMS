<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class LoanRepaymentSchedule extends Model
{
    protected $fillable = [
        'loan_application_id',
        'loan_disbursement_id',
        'total_installments',
        'payment_frequency',
        'installment_amount',
        'total_loan_amount',
        'total_interest',
        'total_fees',
        'total_taxes',
        'total_repayable',
        'grace_period_months',
        'grace_period_end_date',
        'first_payment_date',
        'final_payment_date',
        'installments',
        'installments_paid',
        'installments_pending',
        'installments_overdue',
        'total_paid',
        'total_outstanding',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'installments' => 'array',
        'grace_period_end_date' => 'date',
        'first_payment_date' => 'date',
        'final_payment_date' => 'date',
        'completed_at' => 'date',
        'total_loan_amount' => 'decimal:2',
        'total_interest' => 'decimal:2',
        'total_fees' => 'decimal:2',
        'total_taxes' => 'decimal:2',
        'total_repayable' => 'decimal:2',
        'installment_amount' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'total_outstanding' => 'decimal:2',
    ];

    public function loanApplication(): BelongsTo
    {
        return $this->belongsTo(LoanApplication::class);
    }

    public function loanDisbursement(): BelongsTo
    {
        return $this->belongsTo(LoanDisbursement::class);
    }

    /**
     * Get next unpaid installment
     */
    public function getNextDueInstallment()
    {
        $installments = $this->installments ?? [];
        
        foreach ($installments as $installment) {
            if ($installment['status'] !== 'paid') {
                return $installment;
            }
        }
        
        return null;
    }

    /**
     * Get overdue installments
     */
    public function getOverdueInstallments()
    {
        $installments = $this->installments ?? [];
        $overdue = [];
        
        foreach ($installments as $installment) {
            if ($installment['status'] !== 'paid' && Carbon::parse($installment['due_date'])->isPast()) {
                $overdue[] = $installment;
            }
        }
        
        return $overdue;
    }

    /**
     * Update installment counts
     */
    public function updateCounts()
    {
        $installments = $this->installments ?? [];
        $paid = 0;
        $pending = 0;
        $overdue = 0;
        $totalPaid = 0;

        foreach ($installments as $installment) {
            $totalPaid += $installment['paid_amount'] ?? 0;
            
            if ($installment['status'] === 'paid') {
                $paid++;
            } elseif (Carbon::parse($installment['due_date'])->isPast()) {
                $overdue++;
            } else {
                $pending++;
            }
        }

        $this->update([
            'installments_paid' => $paid,
            'installments_pending' => $pending,
            'installments_overdue' => $overdue,
            'total_paid' => $totalPaid,
            'total_outstanding' => $this->total_repayable - $totalPaid,
            'status' => $paid === $this->total_installments ? 'completed' : $this->status,
            'completed_at' => $paid === $this->total_installments ? now() : null,
        ]);
    }
}
