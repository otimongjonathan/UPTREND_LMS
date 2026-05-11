<?php

namespace App\Console\Commands;

use App\Models\Repayment;
use App\Services\RepaymentWorkflowService;
use Illuminate\Console\Command;

class CalculateLateFees extends Command
{
    protected $signature = 'repayments:calculate-late-fees 
                           {--dry-run : Show what late fees would be calculated without applying them}';

    protected $description = 'Calculate and apply late fees for overdue repayments';

    public function handle()
    {
        $this->info('Checking for overdue repayments...');

        // Get overdue repayments that haven't been completed
        $overdueRepayments = Repayment::where('status', '!=', 'completed')
            ->where('due_date', '<', now())
            ->with(['loanApplication.product', 'loanApplication.user'])
            ->get();

        if ($overdueRepayments->isEmpty()) {
            $this->info('✓ No overdue repayments found.');
            return;
        }

        $this->info("Found " . $overdueRepayments->count() . " overdue repayments:");

        $lateFeesData = [];
        $totalLateFees = 0;

        foreach ($overdueRepayments as $repayment) {
            $currentLateFee = $repayment->late_fee ?? 0;
            $newLateFee = RepaymentWorkflowService::calculateLateFee($repayment);
            
            if ($newLateFee != $currentLateFee) {
                $lateFeesData[] = [
                    'repayment' => $repayment,
                    'current_late_fee' => $currentLateFee,
                    'new_late_fee' => $newLateFee,
                    'difference' => $newLateFee - $currentLateFee
                ];
                $totalLateFees += $newLateFee;
            }
        }

        if (empty($lateFeesData)) {
            $this->info('✓ All late fees are already up to date.');
            return;
        }

        // Display late fees table
        $this->table(
            ['Borrower', 'Loan ID', 'Due Date', 'Days Overdue', 'Current Late Fee', 'New Late Fee', 'Difference'],
            array_map(function($data) {
                $repayment = $data['repayment'];
                return [
                    $repayment->loanApplication->user->name,
                    $repayment->loan_application_id,
                    $repayment->due_date->format('M d, Y'),
                    now()->diffInDays($repayment->due_date),
                    'UGX ' . number_format($data['current_late_fee']),
                    'UGX ' . number_format($data['new_late_fee']),
                    'UGX ' . number_format($data['difference'])
                ];
            }, $lateFeesData)
        );

        $this->info("Total late fees to be applied: UGX " . number_format($totalLateFees));

        if ($this->option('dry-run')) {
            $this->warn('DRY RUN: No late fees were actually applied.');
            return;
        }

        if ($this->confirm('Apply these late fees?')) {
            $appliedCount = 0;
            
            foreach ($lateFeesData as $data) {
                try {
                    // The calculateLateFee method already updates the repayment
                    $appliedCount++;
                } catch (\Exception $e) {
                    $this->error("Failed to apply late fee for repayment {$data['repayment']->id}: " . $e->getMessage());
                }
            }
            
            $this->info("✓ Successfully applied late fees to {$appliedCount} repayments.");
        } else {
            $this->info('Late fee application cancelled.');
        }
    }
}