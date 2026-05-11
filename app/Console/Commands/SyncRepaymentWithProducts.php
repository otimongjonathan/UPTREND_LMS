<?php

namespace App\Console\Commands;

use App\Models\LoanApplication;
use App\Models\LoanProduct;
use App\Services\LoanProductRepaymentSyncService;
use Illuminate\Console\Command;

class SyncRepaymentWithProducts extends Command
{
    protected $signature = 'repayments:sync-products 
                           {--loan= : Specific loan ID}
                           {--product= : Sync all loans for specific product}
                           {--validate : Validate loans against products}';

    protected $description = 'Sync loan repayments with product details (rates, terms, fees)';

    public function handle()
    {
        $this->info('Syncing repayments with product details...');

        if ($loanId = $this->option('loan')) {
            $this->syncSpecificLoan($loanId);
        } elseif ($productId = $this->option('product')) {
            $this->syncProductLoans($productId);
        } elseif ($this->option('validate')) {
            $this->validateLoans();
        } else {
            $this->syncAllLoans();
        }

        $this->info('Sync completed!');
    }

    private function syncSpecificLoan($loanId)
    {
        $loan = LoanApplication::with('product')->find($loanId);
        
        if (!$loan) {
            $this->error("Loan {$loanId} not found");
            return;
        }

        if (!$loan->product) {
            $this->error("Loan {$loanId} has no associated product");
            return;
        }

        try {
            $loan->syncWithProduct();
            $this->info("✓ Synced loan {$loanId} with product '{$loan->product->name}'");
        } catch (\Exception $e) {
            $this->error("✗ Failed to sync loan {$loanId}: " . $e->getMessage());
        }
    }

    private function syncProductLoans($productId)
    {
        $product = LoanProduct::find($productId);
        
        if (!$product) {
            $this->error("Product {$productId} not found");
            return;
        }

        $count = LoanProductRepaymentSyncService::updateRepaymentForProductChange($product);
        $this->info("✓ Synced {$count} loans for product '{$product->name}'");
    }

    private function syncAllLoans()
    {
        $loans = LoanApplication::with('product')
            ->whereNotNull('loan_product_id')
            ->whereIn('status', ['approved', 'active'])
            ->get();

        $synced = 0;
        $errors = 0;

        foreach ($loans as $loan) {
            try {
                $loan->syncWithProduct();
                $synced++;
            } catch (\Exception $e) {
                $this->error("Failed to sync loan {$loan->id}: " . $e->getMessage());
                $errors++;
            }
        }

        $this->info("✓ Synced {$synced} loans");
        if ($errors > 0) {
            $this->warn("⚠ {$errors} loans had errors");
        }
    }

    private function validateLoans()
    {
        $loans = LoanApplication::with('product')
            ->whereNotNull('loan_product_id')
            ->get();

        $totalErrors = 0;

        foreach ($loans as $loan) {
            $errors = LoanProductRepaymentSyncService::validateLoanAgainstProduct($loan);
            
            if (!empty($errors)) {
                $this->warn("⚠ Loan {$loan->id} has issues:");
                foreach ($errors as $error) {
                    $this->line("  - {$error}");
                }
                $totalErrors += count($errors);
            }
        }

        if ($totalErrors === 0) {
            $this->info("✓ All loans are valid against their products");
        } else {
            $this->warn("Found {$totalErrors} validation issues");
        }
    }
}