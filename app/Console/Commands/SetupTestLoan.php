<?php

namespace App\Console\Commands;

use App\Models\LoanApplication;
use App\Services\RepaymentWorkflowService;
use Illuminate\Console\Command;

class SetupTestLoan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:test-loan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup a test loan with active status and repayment schedule';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get first loan or create one
        $loan = LoanApplication::first();

        if (!$loan) {
            $this->error('No loans found in database. Please seed data first.');
            return 1;
        }

        $this->info("Setting up loan #{$loan->id} for testing...");
        $this->line("Customer: {$loan->user->name}");
        $this->line("Amount: UGX " . number_format($loan->amount));

        // Activate the loan
        if ($loan->status !== 'active') {
            $loan->status = 'active';
            $loan->disbursement_date = now();
            $loan->save();
            $this->line("Status: Updated to 'active'");
        } else {
            $this->line("Status: Already active");
        }

        // Generate repayment schedule if not present
        $repaymentCount = $loan->repayments()->count();
        if ($repaymentCount === 0) {
            $this->info("Generating repayment schedule...");
            try {
                RepaymentWorkflowService::generateScheduleOnApproval($loan);
                $this->line("✓ Generated " . $loan->repayments()->count() . " repayments");
            } catch (\Exception $e) {
                $this->error("Error generating schedule: " . $e->getMessage());
                return 1;
            }
        } else {
            $this->line("Repayments: {$repaymentCount} already exist");
        }

        $this->newLine();
        $this->info("✓ Test loan setup complete!");
        $this->line("Run 'php artisan test:loan-tracking' to test the system.");

        return 0;
    }
}
