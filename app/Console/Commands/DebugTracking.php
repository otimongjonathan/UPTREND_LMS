<?php

namespace App\Console\Commands;

use App\Models\LoanApplication;
use App\Models\User;
use Illuminate\Console\Command;

class DebugTracking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:tracking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug tracking system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== CHECKING DATA ===');
        
        // Check users
        $staffUsers = User::where('role', 'staff')->get();
        $this->info('Staff Users:');
        foreach ($staffUsers as $user) {
            $this->line("  ID: {$user->id}, Name: {$user->name}");
        }
        $this->newLine();

        // Check loans
        $loans = LoanApplication::with(['product', 'user'])->get();
        $this->info("Total Loans: " . $loans->count());
        foreach ($loans as $loan) {
            $productName = $loan->product ? $loan->product->name : 'No product';
            $providerId = $loan->product ? $loan->product->provider_id : 'N/A';
            $this->line("  Loan #{$loan->id}: {$loan->user->name} | Product: {$productName} | Provider ID: {$providerId} | Status: {$loan->status}");
        }
        $this->newLine();

        // Check loan tracking for each staff user
        $this->info('=== CHECKING LOAN ASSIGNMENTS ===');
        foreach ($staffUsers as $provider) {
            $providerLoans = LoanApplication::whereHas('product', function ($q) use ($provider) {
                $q->where('provider_id', $provider->id);
            })->where('status', 'active')->get();
            
            $this->line("Provider ID {$provider->id} ({$provider->name}): {$providerLoans->count()} active loans");
        }

        return 0;
    }
}
