<?php

namespace App\Console\Commands;

use App\Models\LoanRepaymentSchedule;
use App\Services\RepaymentScheduleService;
use Illuminate\Console\Command;

class UpdateOverdueRepayments extends Command
{
    protected $signature = 'repayments:update-overdue';
    protected $description = 'Update overdue repayment schedule statuses';

    public function handle()
    {
        $this->info('Updating overdue repayment statuses...');
        
        $schedules = LoanRepaymentSchedule::where('status', 'active')->get();
        
        foreach ($schedules as $schedule) {
            RepaymentScheduleService::updateOverdueStatuses($schedule);
        }
        
        $this->info('Overdue statuses updated successfully!');
        
        return 0;
    }
}
