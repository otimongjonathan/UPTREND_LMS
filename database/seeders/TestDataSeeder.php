<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\LoanApplication;
use App\Models\LoanProduct;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $products = LoanProduct::where('is_active', true)->get();

        if ($customers->isEmpty() || $products->isEmpty()) {
            echo "⚠️  No customers or products found. Skipping test data.\n";
            return;
        }

        $statuses = ['pending', 'approved', 'rejected', 'disbursed'];
        
        foreach ($customers->take(3) as $customer) {
            foreach ($statuses as $status) {
                LoanApplication::create([
                    'user_id' => $customer->id,
                    'loan_product_id' => $products->random()->id,
                    'applicant_full_name' => $customer->name,
                    'amount' => rand(100000, 500000),
                    'term_months' => rand(6, 24),
                    'purpose' => 'Business expansion',
                    'monthly_income' => rand(500000, 2000000),
                    'status' => $status,
                    'application_date' => now(),
                ]);
            }
        }
    }
}
