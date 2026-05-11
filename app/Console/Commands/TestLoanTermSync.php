<?php

namespace App\Console\Commands;

use App\Models\LoanApplication;
use App\Services\LoanTermSyncService;
use Illuminate\Console\Command;

class TestLoanTermSync extends Command
{
    protected $signature = 'loan:test-term-sync {loan_id} {frequency} {due_date}';
    protected $description = 'Test loan term synchronization with different frequencies and due dates';

    public function handle()
    {
        $loanId = $this->argument('loan_id');
        $frequency = $this->argument('frequency');
        $dueDate = $this->argument('due_date');
        
        $loan = LoanApplication::find($loanId);
        
        if (!$loan) {
            $this->error("Loan application {$loanId} not found");
            return 1;
        }
        
        $this->info("Testing loan term sync for Loan #{$loan->id}");
        $this->info("Original terms: {$loan->repayment_schedule}, {$loan->term_months} months");
        $this->info("New frequency: {$frequency}, Due date: {$dueDate}");
        
        // Validate terms
        $validation = LoanTermSyncService::validateTerms($frequency, $dueDate, $loan->disbursement_date);
        
        if (!$validation['valid']) {
            $this->error("Invalid terms: " . $validation['message']);
            return 1;
        }
        
        // Calculate new terms
        $result = LoanTermSyncService::calculateFromDueDate($loan->amount, $frequency, $dueDate, $loan->disbursement_date);
        
        $this->table(['Field', 'Value'], [
            ['Term Months', $result['term_months']],
            ['Installment Count', $result['installment_count']],
            ['Installment Amount', 'UGX ' . number_format($result['installment_amount'], 2)],
            ['First Due Date', $result['first_due_date']],
            ['Final Due Date', $result['final_due_date']],
        ]);
        
        if ($this->confirm('Apply these terms to the loan?')) {
            $updateResult = LoanTermSyncService::regenerateTerms($loan, $frequency, $dueDate);
            
            $loan->update([
                'terms_last_modified' => now(),
                'terms_modified_by' => 1 // System user
            ]);
            
            $this->info("Terms updated successfully!");
            $this->info("New confirmed terms:");
            
            $terms = $loan->fresh()->getEffectiveTerms();
            $this->table(['Field', 'Value'], [
                ['Payment Frequency', $terms['payment_frequency']],
                ['Term Months', $terms['term_months']],
                ['Installment Count', $terms['installment_count']],
                ['Installment Amount', 'UGX ' . number_format($terms['installment_amount'], 2)],
                ['First Due Date', $terms['first_due_date']],
                ['Final Due Date', $terms['final_due_date']],
            ]);
            
            // Show repayment schedule if loan is approved
            if (in_array($loan->status, ['approved', 'active'])) {
                $repayments = $loan->repayments()->where('status', 'pending')->orderBy('due_date')->get();
                
                if ($repayments->count() > 0) {
                    $this->info("\nRepayment Schedule:");
                    $scheduleData = $repayments->map(function($repayment) {
                        return [
                            $repayment->installment_number,
                            'UGX ' . number_format($repayment->amount, 2),
                            $repayment->due_date->format('M d, Y'),
                            $repayment->status
                        ];
                    })->toArray();
                    
                    $this->table(['#', 'Amount', 'Due Date', 'Status'], $scheduleData);
                }
            }
        }
        
        return 0;
    }
}