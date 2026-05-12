<?php

namespace Database\Seeders;

use App\Models\LoanProduct;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoanProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get staff users to assign as providers
        $staffUsers = User::where('role', 'staff')->get();

        if ($staffUsers->isEmpty()) {
            echo "No staff users found. Please create staff users first.\n";
            return;
        }

        $loanProducts = [
            [
                'name' => 'Personal Loan - Quick Cash',
                'description' => 'Fast approval personal loan for your immediate needs. No collateral required.',
                'min_amount' => 5000,
                'max_amount' => 50000,
                'min_term' => 3,
                'max_term' => 12,
                'interest_rate' => 12.5,
                'interest_calculation_method' => 'compound',
                'default_repayment_frequency' => 'monthly',
                'allow_early_payment' => true,
                'early_payment_discount_percent' => 1.0,
                'processing_fee_percent' => 2.0,
                'late_payment_fee_percent' => 5.0,
                'insurance_premium_percent' => 1.5,
                'is_active' => true,
                'requirements' => json_encode(['Valid ID', 'Proof of Income', 'Bank Statement']),
                'features' => json_encode(['Fast Approval', 'No Collateral', 'Flexible Terms']),
            ],
            [
                'name' => 'Business Expansion Loan',
                'description' => 'Grow your business with our competitive business loan. Perfect for expansion and equipment purchase.',
                'min_amount' => 50000,
                'max_amount' => 500000,
                'min_term' => 12,
                'max_term' => 60,
                'interest_rate' => 10.0,
                'interest_calculation_method' => 'compound',
                'default_repayment_frequency' => 'monthly',
                'allow_early_payment' => true,
                'early_payment_discount_percent' => 2.0,
                'processing_fee_percent' => 3.0,
                'late_payment_fee_percent' => 4.0,
                'insurance_premium_percent' => 2.0,
                'is_active' => true,
                'requirements' => json_encode(['Business Registration', 'Financial Statements', 'Business Plan', 'Collateral']),
                'features' => json_encode(['Low Interest Rate', 'Long Term', 'Business Support']),
            ],
            [
                'name' => 'Emergency Loan',
                'description' => 'Quick emergency loan for unexpected expenses. Get approved within 24 hours.',
                'min_amount' => 2000,
                'max_amount' => 20000,
                'min_term' => 1,
                'max_term' => 6,
                'interest_rate' => 15.0,
                'interest_calculation_method' => 'simple',
                'default_repayment_frequency' => 'weekly',
                'allow_early_payment' => true,
                'early_payment_discount_percent' => 0.5,
                'processing_fee_percent' => 1.5,
                'late_payment_fee_percent' => 6.0,
                'insurance_premium_percent' => 1.0,
                'is_active' => true,
                'requirements' => json_encode(['Valid ID', 'Employment Letter']),
                'features' => json_encode(['24-Hour Approval', 'Minimal Requirements', 'Quick Disbursement']),
            ],
            [
                'name' => 'Home Improvement Loan',
                'description' => 'Renovate and improve your home with our affordable home improvement loan.',
                'min_amount' => 30000,
                'max_amount' => 300000,
                'min_term' => 12,
                'max_term' => 36,
                'interest_rate' => 11.0,
                'processing_fee_percent' => 2.5,
                'late_payment_fee_percent' => 4.5,
                'insurance_premium_percent' => 1.8,
                'is_active' => true,
                'requirements' => json_encode(['Property Documents', 'Valid ID', 'Income Proof', 'Renovation Plan']),
                'features' => json_encode(['Competitive Rates', 'Flexible Payment', 'Property Enhancement']),
            ],
            [
                'name' => 'Education Loan',
                'description' => 'Invest in your future with our education loan. Cover tuition, books, and living expenses.',
                'min_amount' => 10000,
                'max_amount' => 100000,
                'min_term' => 12,
                'max_term' => 48,
                'interest_rate' => 9.5,
                'processing_fee_percent' => 1.0,
                'late_payment_fee_percent' => 3.0,
                'insurance_premium_percent' => 1.2,
                'is_active' => true,
                'requirements' => json_encode(['Admission Letter', 'Valid ID', 'Guarantor', 'Academic Records']),
                'features' => json_encode(['Low Interest', 'Grace Period', 'Education Focus']),
            ],
            [
                'name' => 'Agricultural Loan',
                'description' => 'Support your farming business with our specialized agricultural loan. For seeds, equipment, and livestock.',
                'min_amount' => 20000,
                'max_amount' => 200000,
                'min_term' => 6,
                'max_term' => 24,
                'interest_rate' => 8.5,
                'processing_fee_percent' => 2.0,
                'late_payment_fee_percent' => 3.5,
                'insurance_premium_percent' => 2.5,
                'is_active' => true,
                'requirements' => json_encode(['Land Title', 'Farming Plan', 'Valid ID', 'Agricultural Experience']),
                'features' => json_encode(['Seasonal Payment', 'Agricultural Support', 'Low Rates']),
            ],
            [
                'name' => 'Vehicle Purchase Loan',
                'description' => 'Drive your dream car today with our vehicle purchase loan. New and used vehicles accepted.',
                'min_amount' => 100000,
                'max_amount' => 1000000,
                'min_term' => 24,
                'max_term' => 72,
                'interest_rate' => 13.0,
                'processing_fee_percent' => 3.5,
                'late_payment_fee_percent' => 5.0,
                'insurance_premium_percent' => 3.0,
                'is_active' => true,
                'requirements' => json_encode(['Valid ID', 'Proof of Income', 'Down Payment', 'Vehicle Details']),
                'features' => json_encode(['Long Term Payment', 'Comprehensive Insurance', 'Vehicle Ownership']),
            ],
            [
                'name' => 'Salary Advance Loan',
                'description' => 'Get an advance on your salary for urgent needs. Repay automatically from your next salary.',
                'min_amount' => 1000,
                'max_amount' => 15000,
                'min_term' => 1,
                'max_term' => 3,
                'interest_rate' => 18.0,
                'processing_fee_percent' => 1.0,
                'late_payment_fee_percent' => 7.0,
                'insurance_premium_percent' => 0.5,
                'is_active' => true,
                'requirements' => json_encode(['Employment Letter', 'Payslip', 'Valid ID']),
                'features' => json_encode(['Instant Approval', 'Salary Deduction', 'Short Term']),
            ],
        ];

        foreach ($loanProducts as $index => $productData) {
            // Assign provider from staff users (rotate through them)
            $provider = $staffUsers[$index % $staffUsers->count()];
            
            $productData['provider_id'] = $provider->id;
            $productData['provider_company'] = $provider->business_name;

            LoanProduct::updateOrCreate(
                ['name' => $productData['name']],
                $productData
            );
        }

        echo "\n✅ Successfully seeded " . count($loanProducts) . " loan products!\n";
        echo "Providers assigned: " . $staffUsers->count() . " staff users\n\n";
    }
}
