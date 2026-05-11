<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\LoanApplication;
use App\Models\LoanProduct;
use Carbon\Carbon;

class LoanApplicationSeeder extends Seeder
{
    public function run()
    {
        // Create test users first
        $users = $this->createTestUsers();
        
        // Get available loan products
        $products = LoanProduct::where('is_active', true)->get();
        
        if ($products->isEmpty()) {
            $this->command->warn('No active loan products found. Please run: php artisan db:seed --class=LoanProductSeeder');
            return;
        }

        // Create 8 diverse loan applications
        $applications = [
            [
                'user' => $users[0],
                'product' => $products->random(),
                'amount' => 500000,
                'term_months' => 12,
                'purpose' => 'Business expansion - retail shop',
                'status' => 'approved',
                'repayment_schedule' => 'monthly',
                'monthly_income' => 800000,
                'employment_status' => 'self_employed',
                'occupation' => 'Shop Owner',
                'disbursement_date' => Carbon::now()->subDays(30),
            ],
            [
                'user' => $users[1],
                'product' => $products->random(),
                'amount' => 1000000,
                'term_months' => 24,
                'purpose' => 'Agricultural equipment purchase',
                'status' => 'active',
                'repayment_schedule' => 'monthly',
                'monthly_income' => 600000,
                'employment_status' => 'self_employed',
                'occupation' => 'Farmer',
                'disbursement_date' => Carbon::now()->subDays(60),
            ],
            [
                'user' => $users[2],
                'product' => $products->random(),
                'amount' => 300000,
                'term_months' => 6,
                'purpose' => 'Emergency medical expenses',
                'status' => 'overdue',
                'repayment_schedule' => 'monthly',
                'monthly_income' => 450000,
                'employment_status' => 'employed',
                'occupation' => 'Teacher',
                'disbursement_date' => Carbon::now()->subDays(90),
            ],
            [
                'user' => $users[3],
                'product' => $products->random(),
                'amount' => 750000,
                'term_months' => 18,
                'purpose' => 'Home renovation and improvement',
                'status' => 'pending',
                'repayment_schedule' => 'monthly',
                'monthly_income' => 900000,
                'employment_status' => 'employed',
                'occupation' => 'Engineer',
                'disbursement_date' => null,
            ],
            [
                'user' => $users[4],
                'product' => $products->random(),
                'amount' => 200000,
                'term_months' => 12,
                'purpose' => 'Education fees for children',
                'status' => 'approved',
                'repayment_schedule' => 'bi_weekly',
                'monthly_income' => 350000,
                'employment_status' => 'employed',
                'occupation' => 'Nurse',
                'disbursement_date' => Carbon::now()->subDays(15),
            ],
            [
                'user' => $users[5],
                'product' => $products->random(),
                'amount' => 1500000,
                'term_months' => 36,
                'purpose' => 'Transport business - motorcycle purchase',
                'status' => 'active',
                'repayment_schedule' => 'weekly',
                'monthly_income' => 700000,
                'employment_status' => 'self_employed',
                'occupation' => 'Boda Boda Rider',
                'disbursement_date' => Carbon::now()->subDays(45),
            ],
            [
                'user' => $users[6],
                'product' => $products->random(),
                'amount' => 400000,
                'term_months' => 9,
                'purpose' => 'Small scale manufacturing startup',
                'status' => 'rejected',
                'repayment_schedule' => 'monthly',
                'monthly_income' => 250000,
                'employment_status' => 'unemployed',
                'occupation' => 'Entrepreneur',
                'disbursement_date' => null,
            ],
            [
                'user' => $users[7],
                'product' => $products->random(),
                'amount' => 800000,
                'term_months' => 15,
                'purpose' => 'Livestock farming expansion',
                'status' => 'completed',
                'repayment_schedule' => 'monthly',
                'monthly_income' => 550000,
                'employment_status' => 'self_employed',
                'occupation' => 'Livestock Farmer',
                'disbursement_date' => Carbon::now()->subMonths(16),
            ],
        ];

        foreach ($applications as $index => $appData) {
            // Ensure we have a valid product
            if (!$appData['product'] || !$appData['product']->id) {
                $this->command->error("No valid product for application " . ($index + 1));
                continue;
            }
            
            $this->createLoanApplication($appData, $index + 1);
        }

        $this->command->info('Created 8 loan applications for testing');
    }

    private function createTestUsers()
    {
        $users = [];
        
        $userData = [
            [
                'name' => 'John Mukasa',
                'email' => 'john.mukasa@test.com',
                'phone' => '+256701234567',
                'business_name' => 'Mukasa Retail Shop',
                'address' => 'Kampala Central',
                'tel_no' => '+256701234567',
                'role' => 'customer',
                'district_city' => 'Kampala',
                'county' => 'Makindye',
                'gender' => 'male',
                'marital_status' => 'married',
            ],
            [
                'name' => 'Sarah Nakato',
                'email' => 'sarah.nakato@test.com',
                'phone' => '+256702345678',
                'business_name' => 'Nakato Farms',
                'address' => 'Wakiso District',
                'tel_no' => '+256702345678',
                'role' => 'customer',
                'district_city' => 'Wakiso',
                'county' => 'Kyadondo',
                'gender' => 'female',
                'marital_status' => 'single',
            ],
            [
                'name' => 'David Okello',
                'email' => 'david.okello@test.com',
                'phone' => '+256703456789',
                'business_name' => 'Okello Teaching Services',
                'address' => 'Gulu Town',
                'tel_no' => '+256703456789',
                'role' => 'customer',
                'district_city' => 'Gulu',
                'county' => 'Gulu',
                'gender' => 'male',
                'marital_status' => 'married',
            ],
            [
                'name' => 'Grace Namuli',
                'email' => 'grace.namuli@test.com',
                'phone' => '+256704567890',
                'business_name' => 'Namuli Engineering',
                'address' => 'Jinja Industrial Area',
                'tel_no' => '+256704567890',
                'role' => 'customer',
                'district_city' => 'Jinja',
                'county' => 'Jinja',
                'gender' => 'female',
                'marital_status' => 'divorced',
            ],
            [
                'name' => 'Peter Ssemakula',
                'email' => 'peter.ssemakula@test.com',
                'phone' => '+256705678901',
                'business_name' => 'Ssemakula Health Services',
                'address' => 'Mbarara Hospital',
                'tel_no' => '+256705678901',
                'role' => 'customer',
                'district_city' => 'Mbarara',
                'county' => 'Mbarara',
                'gender' => 'male',
                'marital_status' => 'single',
            ],
            [
                'name' => 'Mary Achieng',
                'email' => 'mary.achieng@test.com',
                'phone' => '+256706789012',
                'business_name' => 'Achieng Transport',
                'address' => 'Lira Town',
                'tel_no' => '+256706789012',
                'role' => 'customer',
                'district_city' => 'Lira',
                'county' => 'Lira',
                'gender' => 'female',
                'marital_status' => 'married',
            ],
            [
                'name' => 'James Tumusiime',
                'email' => 'james.tumusiime@test.com',
                'phone' => '+256707890123',
                'business_name' => 'Tumusiime Manufacturing',
                'address' => 'Fort Portal Industrial',
                'tel_no' => '+256707890123',
                'role' => 'customer',
                'district_city' => 'Fort Portal',
                'county' => 'Kabarole',
                'gender' => 'male',
                'marital_status' => 'widowed',
            ],
            [
                'name' => 'Betty Nalwoga',
                'email' => 'betty.nalwoga@test.com',
                'phone' => '+256708901234',
                'business_name' => 'Nalwoga Livestock',
                'address' => 'Masaka District',
                'tel_no' => '+256708901234',
                'role' => 'customer',
                'district_city' => 'Masaka',
                'county' => 'Masaka',
                'gender' => 'female',
                'marital_status' => 'married',
            ],
        ];

        foreach ($userData as $data) {
            $users[] = User::firstOrCreate(
                ['email' => $data['email']],
                array_merge($data, [
                    'password' => bcrypt('password123'),
                    'email_verified_at' => now(),
                ])
            );
        }

        return $users;
    }

    private function createSampleProducts()
    {
        $products = [];
        
        $productData = [
            [
                'name' => 'Quick Cash Loan',
                'description' => 'Fast approval personal loans',
                'min_amount' => 100000,
                'max_amount' => 2000000,
                'min_term' => 3,
                'max_term' => 24,
                'interest_rate' => 18.0,
                'processing_fee_percent' => 2.5,
                'late_payment_fee_percent' => 5.0,
                'insurance_premium_percent' => 1.0,
                'provider_company' => 'UPTREND Financial',
            ],
            [
                'name' => 'Business Growth Loan',
                'description' => 'Loans for business expansion',
                'min_amount' => 500000,
                'max_amount' => 5000000,
                'min_term' => 6,
                'max_term' => 36,
                'interest_rate' => 15.0,
                'processing_fee_percent' => 3.0,
                'late_payment_fee_percent' => 4.0,
                'insurance_premium_percent' => 1.5,
                'provider_company' => 'Business Bank',
            ],
        ];

        foreach ($productData as $data) {
            $products[] = LoanProduct::create(array_merge($data, [
                'provider_id' => 1,
                'is_active' => true,
                'requirements' => ['National ID', 'Proof of Income'],
                'features' => ['Flexible repayment', 'No collateral required'],
            ]));
        }

        return collect($products);
    }

    private function createLoanApplication($data, $index)
    {
        $this->command->info("Creating application {$index} for {$data['user']->name} with product {$data['product']->id} ({$data['product']->name})");
        
        $applicationData = [
            'user_id' => $data['user']->id,
            'loan_product_id' => $data['product']->id,
            'applicant_full_name' => $data['user']->name,
            'dob' => Carbon::now()->subYears(rand(25, 55)),
            'application_date' => Carbon::now()->subDays(rand(1, 90)),
            'district_city' => $data['user']->district_city,
            'county' => $data['user']->county,
            'sub_county' => 'Sub County ' . $index,
            'parish' => 'Parish ' . $index,
            'village' => 'Village ' . $index,
            'residence_status' => rand(0, 1) ? 'owner' : 'tenant',
            'po_box' => 'P.O Box ' . rand(1000, 9999),
            'gender' => $data['user']->gender,
            'marital_status' => $data['user']->marital_status,
            'employment_status' => $data['employment_status'],
            'occupation' => $data['occupation'],
            'loan_type' => 'personal',
            'repayment_schedule' => $data['repayment_schedule'],
            'amount' => $data['amount'],
            'term_months' => $data['term_months'],
            'purpose' => $data['purpose'],
            'monthly_income' => $data['monthly_income'],
            'status' => $data['status'],
            'disbursement_date' => $data['disbursement_date'],
            'disbursed_amount' => in_array($data['status'], ['approved', 'active', 'overdue', 'completed']) ? $data['amount'] : 0,
            'applied_interest_rate' => $data['product']->interest_rate,
            'notes' => 'Test application #' . $index,
        ];
        
        $this->command->info("About to create application with loan_product_id: {$applicationData['loan_product_id']}");
        
        $application = LoanApplication::create($applicationData);

        // Create repayment schedule for approved/active loans
        if (in_array($data['status'], ['approved', 'active', 'overdue', 'completed'])) {
            // Reload the application with product relationship
            $application = $application->fresh('product');
            
            if ($application->product) {
                $application->syncWithProduct();
                
                // Simulate some payments for active/overdue/completed loans
                if (in_array($data['status'], ['active', 'overdue', 'completed'])) {
                    $this->simulatePayments($application, $data['status']);
                }
            } else {
                $this->command->warn("Could not load product for application {$application->id}");
            }
        }

        $this->command->info("Created loan application for {$data['user']->name} - {$data['status']}");
    }

    private function simulatePayments($loan, $status)
    {
        $repayments = $loan->repayments()->orderBy('due_date')->get();
        
        if ($status === 'completed') {
            // Mark all payments as completed
            foreach ($repayments as $repayment) {
                $repayment->update([
                    'paid_amount' => $repayment->amount,
                    'paid_date' => $repayment->due_date,
                    'status' => 'completed',
                    'payment_method' => 'bank_transfer',
                    'payment_reference' => 'TXN' . rand(100000, 999999),
                ]);
            }
        } elseif ($status === 'active') {
            // Pay some installments
            $paidCount = rand(1, min(3, $repayments->count()));
            foreach ($repayments->take($paidCount) as $repayment) {
                $repayment->update([
                    'paid_amount' => $repayment->amount,
                    'paid_date' => $repayment->due_date,
                    'status' => 'completed',
                    'payment_method' => 'mobile_money',
                    'payment_reference' => 'MM' . rand(100000, 999999),
                ]);
            }
        } elseif ($status === 'overdue') {
            // Pay some but leave recent ones overdue
            $paidCount = rand(1, max(1, $repayments->count() - 2));
            foreach ($repayments->take($paidCount) as $repayment) {
                $repayment->update([
                    'paid_amount' => $repayment->amount,
                    'paid_date' => $repayment->due_date,
                    'status' => 'completed',
                    'payment_method' => 'cash',
                    'payment_reference' => 'CASH' . rand(100000, 999999),
                ]);
            }
        }
        
        // Sync loan totals after payments
        $loan->syncWithRepayments();
    }
}