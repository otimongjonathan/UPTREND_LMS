<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Repayment extends Model
{
    protected $fillable = [
        'loan_application_id',
        'installment_number',
        'amount',
        'principal_amount',
        'interest_amount',
        'late_fee',
        'remaining_balance',
        'due_date',
        'original_due_date',
        'paid_date',
        'paid_amount',
        'days_overdue',
        'status',
        'payment_method',
        'payment_reference',
        'payment_frequency',
        'notes',
    ];

    protected $casts = [
        'due_date' => 'date',
        'original_due_date' => 'date',
        'paid_date' => 'date',
        'amount' => 'decimal:2',
        'principal_amount' => 'decimal:2',
        'interest_amount' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'days_overdue' => 'integer',
        'installment_number' => 'integer',
    ];

    public function loanApplication()
    {
        return $this->belongsTo(LoanApplication::class);
    }
    
    /**
     * Check if payment is overdue
     */
    public function isOverdue()
    {
        return $this->status !== 'completed' && Carbon::parse($this->due_date)->isPast();
    }
    
    /**
     * Calculate days overdue
     */
    public function getDaysOverdue()
    {
        if ($this->status === 'completed' || !$this->isOverdue()) {
            return 0;
        }
        
        return Carbon::parse($this->due_date)->diffInDays(now());
    }
    
    /**
     * Get remaining amount to be paid
     */
    public function getRemainingAmount()
    {
        $totalDue = $this->amount + $this->late_fee;
        return max(0, $totalDue - ($this->paid_amount ?? 0));
    }
    
    /**
     * Check if payment is partial
     */
    public function isPartial()
    {
        return $this->status === 'partial' || 
               (($this->paid_amount ?? 0) > 0 && ($this->paid_amount ?? 0) < $this->amount);
    }
    
    /**
     * Scope for overdue payments
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', '!=', 'completed')
                    ->where('due_date', '<', now());
    }
    
    /**
     * Scope for pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    /**
     * Scope for completed payments
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
