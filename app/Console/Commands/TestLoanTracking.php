<?php

namespace App\Console\Commands;

use App\Models\LoanApplication;
use App\Models\Repayment;
use App\Models\PaymentReceipt;
use App\Models\User;
use App\Services\LoanTrackingService;
use App\Services\RepaymentWorkflowService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TestLoanTracking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:loan-tracking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the loan tracking and repayment system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('============================================');
        $this->info('LOAN TRACKING & REPAYMENT SYSTEM TEST');
        $this->info('============================================');
        $this->newLine();

        // 1. Get a business/provider
        $provider = User::where('role', 'staff')
            ->where('id', '!=', 1)  // Skip Staff Admin (ID 1)
            ->first();
        if (!$provider) {
            $this->error('❌ No business provider found. Please seed test data first.');
            return 1;
        }

        $this->line("✓ Provider: {$provider->name}");
        $this->newLine();

        // 2. Get active loans for this provider
        $this->info('--- GETTING ACTIVE LOANS ---');
        $activeLoans = LoanTrackingService::getActiveLoanTracking($provider->id);
        $this->line('Found ' . count($activeLoans) . ' active loans');
        $this->newLine();

        if (empty($activeLoans)) {
            $this->error('No active loans found. Cannot proceed with payment test.');
            return 1;
        }

        // 3. Display tracking for first loan
        $firstLoan = LoanApplication::whereHas('product', function ($q) use ($provider) {
            $q->where('provider_id', $provider->id);
        })->where('status', 'active')->first();

        if ($firstLoan) {
            $this->info('--- LOAN TRACKING DETAILS ---');
            $this->line("Loan ID: {$firstLoan->id}");
            $this->line("Customer: {$firstLoan->user->name}");
            $this->line("Amount: UGX " . number_format($firstLoan->amount));
            $this->line("Status: {$firstLoan->status}");
            $this->newLine();

            $tracking = LoanTrackingService::getLoanTracking($firstLoan);
            $this->line('Repayment Progress:');
            $this->line("  Total Repayable: UGX " . number_format($tracking['amounts']['total_repayable']));
            $this->line("  Amount Paid: UGX " . number_format($tracking['amounts']['total_paid']));
            $this->line("  Outstanding: UGX " . number_format($tracking['amounts']['outstanding_balance']));
            $this->line("  Completion: " . number_format($tracking['progress']['completion_percentage'], 2) . "%");
            $this->line("  Installments: " . $tracking['progress']['paid_installments'] . "/" . $tracking['progress']['total_installments']);
            $this->newLine();

            // 4. Record a test payment
            $this->info('--- RECORDING TEST PAYMENT ---');
            $pendingRepayment = $firstLoan->repayments()
                ->where('status', 'pending')
                ->first();

            if ($pendingRepayment) {
                $this->line("Recording payment for installment #" . $pendingRepayment->installment_number);
                $this->line("Due date: " . $pendingRepayment->due_date->format('Y-m-d'));
                $this->line("Amount: UGX " . number_format($pendingRepayment->amount));
                $this->newLine();

                try {
                    $receipt = RepaymentWorkflowService::recordPayment($pendingRepayment, [
                        'amount' => $pendingRepayment->amount,
                        'payment_date' => Carbon::now(),
                        'payment_method' => 'bank_transfer',
                        'reference_number' => 'TEST-' . uniqid(),
                        'notes' => 'Test payment from tracking system'
                    ]);

                    $this->line('✓ Payment recorded successfully!');
                    $this->line("  Receipt: {$receipt->receipt_number}");
                    $this->line("  Amount: UGX " . number_format($receipt->amount_paid));
                    $this->newLine();

                    // 5. Check updated tracking
                    $this->info('--- UPDATED LOAN TRACKING ---');
                    $updatedTracking = LoanTrackingService::getLoanTracking($firstLoan->fresh());
                    $this->line("Updated Completion: " . number_format($updatedTracking['progress']['completion_percentage'], 2) . "%");
                    $this->line("Updated Outstanding: UGX " . number_format($updatedTracking['amounts']['outstanding_balance']));
                    $this->newLine();
                } catch (\Exception $e) {
                    $this->error("Error recording payment: " . $e->getMessage());
                }
            } else {
                $this->warn('No pending repayments found for this loan.');
            }

            // 6. Display repayment schedule
            $this->info('--- REPAYMENT SCHEDULE ---');
            $schedule = RepaymentWorkflowService::getRepaymentSchedule($firstLoan);
            $this->line("Frequency: {$schedule['repayment_frequency']}");
            $this->line("Total Installments: {$schedule['total_installments']}");
            $this->line("First Due: " . (new Carbon($schedule['first_due_date']))->format('Y-m-d'));
            $this->line("Final Due: " . (new Carbon($schedule['final_due_date']))->format('Y-m-d'));
            $this->newLine();
            $this->line("Next 5 Installments:");

            foreach (array_slice($schedule['installments'], 0, 5) as $installment) {
                $statusEmoji = match($installment['status']) {
                    'completed' => '✓',
                    'partial' => '⊡',
                    default => '◯'
                };
                $this->line("  {$statusEmoji} #{$installment['installment_number']}: " .
                            "UGX " . number_format($installment['total_amount']) .
                            " (Due: " . (new Carbon($installment['due_date']))->format('M d, Y') . ")");
            }
            $this->newLine();
        }

        // 7. Display analytics
        $this->info('--- ANALYTICS ---');
        $analytics = RepaymentWorkflowService::getRepaymentAnalytics($provider->id);
        $this->line("Total Repayments: " . $analytics['total_repayments']);
        $this->line("Completed: " . $analytics['completed_repayments']);
        $this->line("Overdue: " . $analytics['overdue_repayments']);
        $this->line("Completion Rate: " . number_format($analytics['completion_rate'], 2) . "%");
        $this->line("Overdue Rate: " . number_format($analytics['overdue_rate'], 2) . "%");
        $this->newLine();

        // 8. Check for overdue loans
        $this->info('--- OVERDUE LOANS ---');
        $overdueLoans = LoanTrackingService::getOverdueLoans($provider->id);
        if (count($overdueLoans) > 0) {
            $this->line("Found " . count($overdueLoans) . " overdue loan(s):");
            foreach (array_slice($overdueLoans, 0, 3) as $loan) {
                $this->line("  - {$loan->user->name}: UGX " . number_format($loan->outstanding_balance) . " outstanding");
            }
        } else {
            $this->line("No overdue loans");
        }
        $this->newLine();

        $this->info('============================================');
        $this->info('TEST COMPLETED SUCCESSFULLY');
        $this->info('============================================');

        return 0;
    }
}
