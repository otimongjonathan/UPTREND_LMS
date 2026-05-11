<?php

namespace App\Console\Commands;

use Database\Seeders\LoanApplicationSeeder;
use Illuminate\Console\Command;

class SeedLoanApplications extends Command
{
    protected $signature = 'seed:loan-applications {--fresh : Delete existing test applications first}';
    protected $description = 'Seed 8 test loan applications with different users and scenarios';

    public function handle()
    {
        if ($this->option('fresh')) {
            $this->info('Cleaning up existing test applications...');
            
            // Delete test users and their applications
            \App\Models\User::where('email', 'like', '%@test.com')->delete();
            
            $this->info('✓ Cleaned up existing test data');
        }

        $this->info('Seeding loan applications...');
        
        $seeder = new LoanApplicationSeeder();
        $seeder->setCommand($this);
        $seeder->run();
        
        $this->newLine();
        $this->info('🎉 Successfully created 8 test loan applications!');
        
        // Display summary
        $this->displaySummary();
    }

    private function displaySummary()
    {
        $applications = \App\Models\LoanApplication::with('user', 'product')
            ->whereHas('user', function($query) {
                $query->where('email', 'like', '%@test.com');
            })
            ->get();

        $this->table(
            ['User', 'Amount', 'Term', 'Status', 'Purpose', 'Product'],
            $applications->map(function($app) {
                return [
                    $app->user->name,
                    'UGX ' . number_format($app->amount),
                    $app->term_months . ' months',
                    ucfirst($app->status),
                    substr($app->purpose, 0, 30) . '...',
                    $app->product->name ?? 'N/A'
                ];
            })
        );

        $statusCounts = $applications->groupBy('status')->map->count();
        
        $this->info('Status Distribution:');
        foreach ($statusCounts as $status => $count) {
            $this->line("  {$status}: {$count} applications");
        }
    }
}