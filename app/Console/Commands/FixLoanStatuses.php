<?php

namespace App\Console\Commands;

use App\Models\LoanApplication;
use Illuminate\Console\Command;

class FixLoanStatuses extends Command
{
    protected $signature = 'loans:fix-statuses';
    protected $description = 'Fix loan statuses - approved loans should not be marked as active unless disbursed';

    public function handle()
    {
        $this->info('Checking loan statuses...');
        
        // Find loans marked as 'active' but have no disbursement
        $incorrectActive = LoanApplication::where('status', 'active')
            ->whereDoesntHave('disbursements', function($query) {
                $query->where('status', 'disbursed');
            })
            ->get();
        
        if ($incorrectActive->count() > 0) {
            $this->warn("Found {$incorrectActive->count()} loans marked as 'active' but not disbursed.");
            
            foreach ($incorrectActive as $loan) {
                $this->line("Loan #{$loan->id} - Changing from 'active' to 'approved'");
                $loan->update(['status' => 'approved']);
            }
            
            $this->info("Fixed {$incorrectActive->count()} loan statuses.");
        } else {
            $this->info('All loan statuses are correct!');
        }
        
        // Summary
        $this->newLine();
        $this->info('Current Status Summary:');
        $this->table(
            ['Status', 'Count'],
            [
                ['pending', LoanApplication::where('status', 'pending')->count()],
                ['approved', LoanApplication::where('status', 'approved')->count()],
                ['active', LoanApplication::where('status', 'active')->count()],
                ['completed', LoanApplication::where('status', 'completed')->count()],
                ['overdue', LoanApplication::where('status', 'overdue')->count()],
                ['rejected', LoanApplication::where('status', 'rejected')->count()],
            ]
        );
        
        return 0;
    }
}
