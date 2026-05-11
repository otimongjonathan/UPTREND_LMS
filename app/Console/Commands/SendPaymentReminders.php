<?php

namespace App\Console\Commands;

use App\Services\RepaymentWorkflowService;
use Illuminate\Console\Command;

class SendPaymentReminders extends Command
{
    protected $signature = 'repayments:send-reminders 
                           {--dry-run : Show what reminders would be sent without actually sending them}';

    protected $description = 'Send payment reminders to borrowers based on their repayment frequency';

    public function handle()
    {
        $this->info('Checking for repayments that need reminders...');

        $reminders = RepaymentWorkflowService::getRepaymentsForReminders();

        if (empty($reminders)) {
            $this->info('✓ No payment reminders needed at this time.');
            return;
        }

        $this->info("Found " . count($reminders) . " repayments that need reminders:");

        // Display reminders table
        $this->table(
            ['Borrower', 'Loan ID', 'Amount Due', 'Due Date', 'Days Until Due', 'Reminder Type', 'Frequency'],
            array_map(function($reminder) {
                $repayment = $reminder['repayment'];
                return [
                    $repayment->loanApplication->user->name,
                    $repayment->loan_application_id,
                    'UGX ' . number_format($repayment->amount),
                    $repayment->due_date->format('M d, Y'),
                    $reminder['days_until_due'],
                    ucfirst($reminder['reminder_type']),
                    ucfirst($repayment->loanApplication->repayment_schedule)
                ];
            }, $reminders)
        );

        if ($this->option('dry-run')) {
            $this->warn('DRY RUN: No reminders were actually sent.');
            return;
        }

        if ($this->confirm('Send these payment reminders?')) {
            $sentCount = RepaymentWorkflowService::sendPaymentReminders();
            $this->info("✓ Successfully sent {$sentCount} payment reminders.");
        } else {
            $this->info('Reminder sending cancelled.');
        }
    }
}