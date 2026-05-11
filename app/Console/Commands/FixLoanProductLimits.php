<?php

namespace App\Console\Commands;

use App\Services\LoanProductValidationService;
use Illuminate\Console\Command;

class FixLoanProductLimits extends Command
{
    protected $signature = 'loans:fix-limits 
                           {--provider= : Fix only for specific provider ID}
                           {--check : Only check for violations without fixing}';

    protected $description = 'Fix loan applications that violate product amount and term limits';

    public function handle()
    {
        $providerId = $this->option('provider');
        
        if ($this->option('check')) {
            $this->checkViolations($providerId);
        } else {
            $this->fixViolations($providerId);
        }
    }

    private function checkViolations($providerId)
    {
        $this->info('Checking for loan applications that violate product limits...');
        
        $violations = LoanProductValidationService::getViolatingApplications($providerId);
        
        if (empty($violations)) {
            $this->info('✓ No violations found. All loan applications are within product limits.');
            return;
        }

        $this->warn("Found " . count($violations) . " applications with violations:");
        
        foreach ($violations as $violation) {
            $loan = $violation['loan'];
            $this->line("");
            $this->line("Loan ID: {$loan->id} | {$loan->applicant_full_name}");
            
            if ($loan->product) {
                $this->line("Product: {$loan->product->name}");
                $this->line("Current Amount: UGX " . number_format($loan->amount));
                $this->line("Product Limits: UGX " . number_format($loan->product->min_amount) . " - UGX " . number_format($loan->product->max_amount));
            } else {
                $this->line("Product: No product associated");
                $this->line("Current Amount: UGX " . number_format($loan->amount));
            }
            
            foreach ($violation['errors'] as $error) {
                $this->error("  - {$error}");
            }
        }
        
        $this->line("");
        $this->info("Run without --check flag to fix these violations automatically.");
    }

    private function fixViolations($providerId)
    {
        $this->info('Fixing loan applications that violate product limits...');
        
        $fixed = LoanProductValidationService::fixLoanApplicationLimits($providerId);
        
        if (empty($fixed)) {
            $this->info('✓ No violations found. All loan applications are within product limits.');
            return;
        }

        $this->info("Fixed " . count($fixed) . " applications:");
        
        $this->table(
            ['Loan ID', 'Applicant', 'Product', 'Old Amount', 'New Amount'],
            array_map(function($fix) {
                return [
                    $fix['loan_id'],
                    $fix['applicant'],
                    $fix['product'],
                    'UGX ' . number_format($fix['old_amount']),
                    'UGX ' . number_format($fix['new_amount']),
                ];
            }, $fixed)
        );
        
        $this->info('✓ All violations have been fixed and repayment schedules updated.');
    }
}