<?php

namespace App\Console\Commands;

use App\Models\LoanRepaymentSchedule;
use App\Notifications\RepaymentDueReminder;
use App\Notifications\RepaymentOverdue;
use Illuminate\Console\Command;

class SendRepaymentReminders extends Command
{
    protected $signature = 'repayments:send-reminders';
    protected $description = 'Send repayment reminders to customers';

    public function handle()
    {
        $this->info('Sending repayment reminders...');

        $schedules = LoanRepaymentSchedule::with('loanApplication.user')
            ->where('status', 'active')
            ->get();

        $remindersSent = 0;
        $overdueSent = 0;

        foreach ($schedules as $schedule) {
            $installments = $schedule->installments;
            
            foreach ($installments as $installment) {
                if ($installment['status'] === 'paid') {
                    continue;
                }

                $dueDate = \Carbon\Carbon::parse($installment['due_date']);
                $today = now();
                $daysUntilDue = $today->diffInDays($dueDate, false);

                // Send reminder 7 days before, 3 days before, and 1 day before
                if (in_array($daysUntilDue, [7, 3, 1])) {
                    $schedule->loanApplication->user->notify(
                        new RepaymentDueReminder(
                            $schedule->loan_application_id,
                            $installment['installment_number'],
                            $installment['total_due'],
                            $dueDate->format('M d, Y'),
                            $daysUntilDue
                        )
                    );
                    $remindersSent++;
                    $this->info("Reminder sent for Loan #{$schedule->loan_application_id}, Installment #{$installment['installment_number']}");
                }

                // Send overdue notification
                if ($daysUntilDue < 0 && $installment['status'] === 'overdue') {
                    $daysOverdue = abs($daysUntilDue);
                    $lateFee = $installment['late_fee'] ?? 0;
                    
                    $schedule->loanApplication->user->notify(
                        new RepaymentOverdue(
                            $schedule->loan_application_id,
                            $installment['installment_number'],
                            $installment['total_due'],
                            $lateFee,
                            $daysOverdue
                        )
                    );
                    $overdueSent++;
                    $this->warn("Overdue notice sent for Loan #{$schedule->loan_application_id}, Installment #{$installment['installment_number']}");
                }
            }
        }

        $this->info("Reminders sent: {$remindersSent}");
        $this->warn("Overdue notices sent: {$overdueSent}");
        $this->info('Done!');

        return 0;
    }
}
