<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\LoanApplication;
use App\Models\LoanProduct;
use App\Models\Repayment;
use App\Models\LoanDisbursement;

class DatabaseHealthCheck extends Command
{
    protected $signature = 'db:health-check';
    protected $description = 'Check database health and data consistency';

    public function handle()
    {
        $this->info('🔍 Running Database Health Check...');
        $this->newLine();

        // Check tables exist
        $this->checkTables();
        
        // Check data counts
        $this->checkDataCounts();
        
        // Check foreign key integrity
        $this->checkForeignKeyIntegrity();
        
        // Check for orphaned records
        $this->checkOrphanedRecords();
        
        $this->newLine();
        $this->info('✅ Database health check completed!');
    }

    private function checkTables()
    {
        $this->info('📋 Checking Tables...');
        
        $requiredTables = [
            'users', 'loan_applications', 'loan_products', 'repayments',
            'loan_disbursements', 'collaterals', 'loan_guarantors',
            'credit_scores', 'payment_receipts', 'audit_logs'
        ];

        foreach ($requiredTables as $table) {
            $exists = DB::getSchemaBuilder()->hasTable($table);
            $this->line("  {$table}: " . ($exists ? '✓' : '✗'));
        }
        $this->newLine();
    }

    private function checkDataCounts()
    {
        $this->info('📊 Data Counts...');
        
        $this->line('  Users: ' . User::count());
        $this->line('    - Staff: ' . User::where('role', 'staff')->count());
        $this->line('    - Customers: ' . User::where('role', 'customer')->count());
        $this->line('  Loan Products: ' . LoanProduct::count());
        $this->line('  Loan Applications: ' . LoanApplication::count());
        $this->line('    - Pending: ' . LoanApplication::where('status', 'pending')->count());
        $this->line('    - Approved: ' . LoanApplication::where('status', 'approved')->count());
        $this->line('    - Rejected: ' . LoanApplication::where('status', 'rejected')->count());
        $this->line('    - Disbursed: ' . LoanApplication::where('status', 'disbursed')->count());
        $this->line('  Repayments: ' . Repayment::count());
        $this->line('  Disbursements: ' . LoanDisbursement::count());
        $this->newLine();
    }

    private function checkForeignKeyIntegrity()
    {
        $this->info('🔗 Checking Foreign Key Integrity...');
        
        // Check loan applications have valid users
        $invalidUserApps = LoanApplication::whereNotIn('user_id', User::pluck('id'))->count();
        $this->line('  Invalid user_id in loan_applications: ' . ($invalidUserApps > 0 ? "⚠️  {$invalidUserApps}" : '✓ 0'));
        
        // Check loan applications have valid products
        $invalidProductApps = LoanApplication::whereNotNull('loan_product_id')
            ->whereNotIn('loan_product_id', LoanProduct::pluck('id'))->count();
        $this->line('  Invalid loan_product_id in loan_applications: ' . ($invalidProductApps > 0 ? "⚠️  {$invalidProductApps}" : '✓ 0'));
        
        // Check loan products have valid providers
        $invalidProviders = LoanProduct::whereNotIn('provider_id', User::pluck('id'))->count();
        $this->line('  Invalid provider_id in loan_products: ' . ($invalidProviders > 0 ? "⚠️  {$invalidProviders}" : '✓ 0'));
        
        $this->newLine();
    }

    private function checkOrphanedRecords()
    {
        $this->info('🔍 Checking for Orphaned Records...');
        
        // Check repayments without loan applications
        $orphanedRepayments = Repayment::whereNotIn('loan_application_id', LoanApplication::pluck('id'))->count();
        $this->line('  Orphaned repayments: ' . ($orphanedRepayments > 0 ? "⚠️  {$orphanedRepayments}" : '✓ 0'));
        
        // Check disbursements without loan applications
        $orphanedDisbursements = LoanDisbursement::whereNotIn('loan_application_id', LoanApplication::pluck('id'))->count();
        $this->line('  Orphaned disbursements: ' . ($orphanedDisbursements > 0 ? "⚠️  {$orphanedDisbursements}" : '✓ 0'));
        
        $this->newLine();
    }
}
