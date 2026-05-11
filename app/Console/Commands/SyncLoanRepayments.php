<?php

namespace App\Console\Commands;

use App\Models\LoanApplication;
use App\Services\LoanRepaymentSyncService;
use Illuminate\Console\Command;

class SyncLoanRepayments extends Command
{
    protected $signature = 'loans:sync-repayments 
                           {--loan= : Specific loan ID to sync}
                           {--validate : Validate consistency without fixing}
                           {--fix : Fix found issues}';

    protected $description = 'Sync loan details with repayment data';

    public function handle()
    {
        $this->info('Starting loan-repayment synchronization...');

        if ($loanId = $this->option('loan')) {
            $this->syncSpecificLoan($loanId);
        } else {
            $this->syncAllLoans();
        }

        $this->info('Synchronization completed!');
    }

    private function syncSpecificLoan($loanId)
    {
        $loan = LoanApplication::find($loanId);
        
        if (!$loan) {
            $this->error("Loan with ID {$loanId} not found");
            return;
        }

        $this->info("Processing loan ID: {$loan->id}");

        if ($this->option('validate')) {
            $this->validateLoan($loan);
        } elseif ($this->option('fix')) {
            $this->fixLoan($loan);
        } else {
            LoanRepaymentSyncService::syncLoanWithRepayments($loan);
            $this->info("✓ Loan {$loan->id} synced successfully");
        }
    }

    private function syncAllLoans()
    {
        $syncedCount = LoanRepaymentSyncService::syncAllLoans();
        $this->info("✓ Synced {$syncedCount} loans");

        if ($this->option('validate')) {
            $this->validateAllLoans();
        }
    }

    private function validateLoan(LoanApplication $loan)
    {
        $issues = LoanRepaymentSyncService::validateLoanRepaymentConsistency($loan);
        
        if (empty($issues)) {
            $this->info("✓ Loan {$loan->id} is consistent");
        } else {
            $this->warn("⚠ Loan {$loan->id} has issues:");
            foreach ($issues as $issue) {
                $this->line("  - {$issue}");
            }
        }
    }

    private function fixLoan(LoanApplication $loan)
    {
        $fixed = LoanRepaymentSyncService::fixLoanRepaymentIssues($loan);
        
        if (empty($fixed)) {
            $this->info("✓ Loan {$loan->id} had no issues to fix");
        } else {
            $this->info("✓ Fixed issues for loan {$loan->id}:");
            foreach ($fixed as $fix) {
                $this->line("  - {$fix}");
            }
        }
    }

    private function validateAllLoans()
    {
        $loans = LoanApplication::whereIn('status', ['approved', 'active', 'overdue'])
            ->with('repayments')
            ->get();

        $totalIssues = 0;

        foreach ($loans as $loan) {
            $issues = LoanRepaymentSyncService::validateLoanRepaymentConsistency($loan);
            if (!empty($issues)) {
                $totalIssues += count($issues);
                $this->warn("⚠ Loan {$loan->id} has " . count($issues) . " issue(s)");
            }
        }

        if ($totalIssues === 0) {
            $this->info("✓ All loans are consistent");
        } else {
            $this->warn("Found {$totalIssues} total issues across all loans");
            $this->info("Run with --fix option to automatically fix issues");
        }
    }
}