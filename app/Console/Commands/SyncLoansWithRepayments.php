<?php

namespace App\Console\Commands;

use App\Models\LoanApplication;
use App\Services\LoanRepaymentSyncService;
use Illuminate\Console\Command;

class SyncLoansWithRepayments extends Command
{
    protected $signature = 'loans:sync {--loan= : Specific loan ID}';
    protected $description = 'Sync loan details with repayment data';

    public function handle()
    {
        if ($loanId = $this->option('loan')) {
            $loan = LoanApplication::find($loanId);
            if ($loan) {
                $loan->syncWithRepayments();
                $this->info("Synced loan {$loanId}");
            } else {
                $this->error("Loan {$loanId} not found");
            }
        } else {
            $loans = LoanApplication::whereIn('status', ['approved', 'active', 'overdue'])->get();
            foreach ($loans as $loan) {
                $loan->syncWithRepayments();
            }
            $this->info("Synced {$loans->count()} loans");
        }
    }
}