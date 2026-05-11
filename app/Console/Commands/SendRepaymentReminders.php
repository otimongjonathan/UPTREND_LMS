<?php

namespace App\Console\Commands;

use App\Services\RepaymentNotificationService;
use Illuminate\Console\Command;

class SendRepaymentReminders extends Command
{
    protected $signature = 'repayments:send-reminders';
    protected $description = 'Send repayment reminder notifications to customers';

    public function handle()
    {
        $this->info('Starting repayment reminder notifications...');
        
        $notificationService = app(RepaymentNotificationService::class);
        $results = $notificationService->sendRepaymentReminders();
        
        $this->info('Repayment reminders sent successfully!');
        $this->table(
            ['Type', 'Count'],
            [
                ['Due Today', $results['due_today']],
                ['Due Tomorrow', $results['due_tomorrow']],
                ['Due in 3 Days', $results['due_in_3_days']],
                ['Overdue', $results['overdue']],
            ]
        );
        
        $total = array_sum($results);
        $this->info("Total notifications sent: {$total}");
        
        return Command::SUCCESS;
    }
}