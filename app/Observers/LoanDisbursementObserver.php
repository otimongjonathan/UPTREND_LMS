<?php

namespace App\Observers;

use App\Models\LoanDisbursement;
use App\Services\RepaymentScheduleService;

class LoanDisbursementObserver
{
    public function created(LoanDisbursement $disbursement): void
    {
        if ($disbursement->status === 'disbursed') {
            RepaymentScheduleService::generateSchedule($disbursement);
        }
    }

    public function updated(LoanDisbursement $disbursement): void
    {
        if ($disbursement->isDirty('status') && $disbursement->status === 'disbursed') {
            RepaymentScheduleService::generateSchedule($disbursement);
        }
    }
}
