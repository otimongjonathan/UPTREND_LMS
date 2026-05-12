<?php

namespace App\Console\Commands;

use App\Models\LoanApplication;
use App\Models\LoanProduct;
use App\Models\User;
use App\Services\ComprehensiveNotificationService;
use Illuminate\Console\Command;

class TestNotifications extends Command
{
    protected $signature = 'notifications:test {type?}';
    protected $description = 'Test notification system';

    public function handle()
    {
        $type = $this->argument('type');

        if (!$type) {
            $this->info('Available notification types:');
            $this->line('  account-created          - Test account creation notification');
            $this->line('  loan-submitted           - Test new loan application notification (staff)');
            $this->line('  loan-approved            - Test loan approval notification (customer)');
            $this->line('  loan-rejected            - Test loan rejection notification (customer)');
            $this->line('  loan-disbursed           - Test loan disbursement notification (customer)');
            $this->line('  repayment-overdue        - Test overdue repayment notification (both)');
            $this->line('  loan-rescheduling        - Test rescheduling request notification (staff)');
            $this->line('  indulgence-request       - Test indulgence request notification (staff)');
            $this->line('  complaint                - Test complaint notification (staff)');
            $this->line('  new-product              - Test new product notification (customers)');
            $this->line('');
            $this->line('Usage: php artisan notifications:test {type}');
            return;
        }

        $this->info("Testing {$type} notification...");

        try {
            switch ($type) {
                case 'account-created':
                    $this->testAccountCreated();
                    break;
                case 'loan-submitted':
                    $this->testLoanSubmitted();
                    break;
                case 'loan-approved':
                    $this->testLoanApproved();
                    break;
                case 'loan-rejected':
                    $this->testLoanRejected();
                    break;
                case 'loan-disbursed':
                    $this->testLoanDisbursed();
                    break;
                case 'repayment-overdue':
                    $this->testRepaymentOverdue();
                    break;
                case 'loan-rescheduling':
                    $this->testLoanRescheduling();
                    break;
                case 'indulgence-request':
                    $this->testIndulgenceRequest();
                    break;
                case 'complaint':
                    $this->testComplaint();
                    break;
                case 'new-product':
                    $this->testNewProduct();
                    break;
                default:
                    $this->error("Unknown notification type: {$type}");
                    return 1;
            }

            $this->info('✅ Notification sent successfully!');
            $this->line('Check your email and database notifications table.');
        } catch (\Exception $e) {
            $this->error('❌ Failed to send notification: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    private function testAccountCreated()
    {
        $user = User::where('role', 'customer')->first();
        if (!$user) {
            throw new \Exception('No customer found in database');
        }
        ComprehensiveNotificationService::notifyAccountCreated($user);
        $this->line("Sent to: {$user->email}");
    }

    private function testLoanSubmitted()
    {
        $application = LoanApplication::first();
        if (!$application) {
            throw new \Exception('No loan application found in database');
        }
        ComprehensiveNotificationService::notifyStaffNewApplication($application);
        $staffCount = User::where('role', 'staff')->count();
        $this->line("Sent to: {$staffCount} staff members");
    }

    private function testLoanApproved()
    {
        $application = LoanApplication::first();
        if (!$application) {
            throw new \Exception('No loan application found in database');
        }
        ComprehensiveNotificationService::notifyLoanPendingFinalApproval($application);
        $this->line("Sent to: {$application->user->email}");
    }

    private function testLoanRejected()
    {
        $application = LoanApplication::first();
        if (!$application) {
            throw new \Exception('No loan application found in database');
        }
        ComprehensiveNotificationService::notifyLoanRejected($application, 'Test rejection reason');
        $this->line("Sent to: {$application->user->email}");
    }

    private function testLoanDisbursed()
    {
        $application = LoanApplication::first();
        if (!$application) {
            throw new \Exception('No loan application found in database');
        }
        ComprehensiveNotificationService::notifyLoanDisbursed($application, $application->amount);
        $this->line("Sent to: {$application->user->email}");
    }

    private function testRepaymentOverdue()
    {
        $application = LoanApplication::first();
        if (!$application) {
            throw new \Exception('No loan application found in database');
        }
        ComprehensiveNotificationService::handleOverdueRepayment(
            $application,
            1, // installment number
            50000, // amount
            5000, // late fee
            5 // days overdue
        );
        $staffCount = User::where('role', 'staff')->count();
        $this->line("Sent to: {$application->user->email} and {$staffCount} staff members");
    }

    private function testLoanRescheduling()
    {
        $application = LoanApplication::first();
        if (!$application) {
            throw new \Exception('No loan application found in database');
        }
        ComprehensiveNotificationService::notifyStaffLoanRescheduling(
            $application,
            'Financial difficulties due to business slowdown',
            '2026-06-01'
        );
        $staffCount = User::where('role', 'staff')->count();
        $this->line("Sent to: {$staffCount} staff members");
    }

    private function testIndulgenceRequest()
    {
        $application = LoanApplication::first();
        if (!$application) {
            throw new \Exception('No loan application found in database');
        }
        ComprehensiveNotificationService::notifyStaffIndulgenceRequest(
            $application,
            'Unexpected medical expenses',
            30 // extension days
        );
        $staffCount = User::where('role', 'staff')->count();
        $this->line("Sent to: {$staffCount} staff members");
    }

    private function testComplaint()
    {
        $customer = User::where('role', 'customer')->first();
        if (!$customer) {
            throw new \Exception('No customer found in database');
        }
        ComprehensiveNotificationService::notifyStaffComplaint(
            $customer,
            'Delayed loan disbursement',
            'My loan was approved 5 days ago but I have not received the funds yet.',
            1, // loan ID
            'high'
        );
        $staffCount = User::where('role', 'staff')->count();
        $this->line("Sent to: {$staffCount} staff members");
    }

    private function testNewProduct()
    {
        $product = LoanProduct::first();
        if (!$product) {
            throw new \Exception('No loan product found in database');
        }
        ComprehensiveNotificationService::notifyNewLoanProduct($product);
        $customerCount = User::where('role', 'customer')->count();
        $this->line("Sent to: {$customerCount} customers");
    }
}
