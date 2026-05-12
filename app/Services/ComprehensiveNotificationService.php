<?php

namespace App\Services;

use App\Models\LoanApplication;
use App\Models\LoanDisbursement;
use App\Models\LoanProduct;
use App\Models\User;
use App\Notifications\AccountCreated;
use App\Notifications\ComplaintSubmitted;
use App\Notifications\IndulgenceRequest;
use App\Notifications\LoanApplicationApproved;
use App\Notifications\LoanApplicationPendingFinalApproval;
use App\Notifications\LoanApplicationRejected;
use App\Notifications\LoanApplicationSubmitted;
use App\Notifications\LoanDisbursed;
use App\Notifications\LoanReschedulingRequest;
use App\Notifications\NewLoanProductAvailable;
use App\Notifications\RepaymentOverdue;
use App\Notifications\RepaymentOverdueStaff;
use Illuminate\Support\Facades\Notification;

class ComprehensiveNotificationService
{
    // ==================== CUSTOMER NOTIFICATIONS ====================

    /**
     * Send account creation notification to customer
     */
    public static function notifyAccountCreated(User $user)
    {
        $user->notify(new AccountCreated($user));
    }

    /**
     * Send loan application approved notification (pending final approval)
     */
    public static function notifyLoanPendingFinalApproval(LoanApplication $application)
    {
        $application->user->notify(new LoanApplicationPendingFinalApproval($application));
    }

    /**
     * Send loan disbursement notification to customer
     */
    public static function notifyLoanDisbursed(LoanDisbursement $disbursement)
    {
        $disbursement->loanApplication->user->notify(new LoanDisbursed($disbursement));
    }

    /**
     * Send loan overdue notification to customer
     */
    public static function notifyCustomerLoanOverdue(
        LoanApplication $application,
        int $installmentNumber,
        float $amount,
        float $lateFee,
        int $daysOverdue
    ) {
        $application->user->notify(new RepaymentOverdue(
            $application->id,
            $installmentNumber,
            $amount,
            $lateFee,
            $daysOverdue
        ));
    }

    /**
     * Send new loan product notification to customers
     */
    public static function notifyNewLoanProduct(LoanProduct $product)
    {
        $customers = User::query()->get()->filter(fn ($user) => $user->role === 'customer')->values();
        Notification::send($customers, new NewLoanProductAvailable($product));
    }

    /**
     * Send loan application approved notification
     */
    public static function notifyLoanApproved(LoanApplication $application)
    {
        $application->user->notify(new LoanApplicationApproved($application));
    }

    /**
     * Send loan application rejected notification
     */
    public static function notifyLoanRejected(LoanApplication $application, string $reason = '')
    {
        $application->user->notify(new LoanApplicationRejected($application, $reason));
    }

    // ==================== STAFF NOTIFICATIONS ====================

    /**
     * Send new loan application notification to staff
     */
    public static function notifyStaffNewApplication(LoanApplication $application)
    {
        $staff = User::query()->get()->filter(fn ($user) => User::isStaffRole($user->role))->values();
        Notification::send($staff, new LoanApplicationSubmitted($application));
    }

    /**
     * Send repayment overdue notification to staff
     */
    public static function notifyStaffRepaymentOverdue(
        LoanApplication $application,
        int $installmentNumber,
        float $amount,
        float $lateFee,
        int $daysOverdue
    ) {
        $staff = User::query()->get()->filter(fn ($user) => User::isStaffRole($user->role))->values();
        Notification::send($staff, new RepaymentOverdueStaff(
            $application,
            $installmentNumber,
            $amount,
            $lateFee,
            $daysOverdue
        ));
    }

    /**
     * Send loan rescheduling request to staff
     */
    public static function notifyStaffLoanRescheduling(
        LoanApplication $application,
        string $reason,
        ?string $proposedDate = null
    ) {
        $staff = User::query()->get()->filter(fn ($user) => User::isStaffRole($user->role))->values();
        Notification::send($staff, new LoanReschedulingRequest($application, $reason, $proposedDate));
    }

    /**
     * Send indulgence request to staff
     */
    public static function notifyStaffIndulgenceRequest(
        LoanApplication $application,
        string $reason,
        ?int $extensionDays = null
    ) {
        $staff = User::query()->get()->filter(fn ($user) => User::isStaffRole($user->role))->values();
        Notification::send($staff, new IndulgenceRequest($application, $reason, $extensionDays));
    }

    /**
     * Send complaint notification to staff
     */
    public static function notifyStaffComplaint(
        User $customer,
        string $subject,
        string $description,
        ?int $loanId = null,
        string $priority = 'normal'
    ) {
        $staff = User::query()->get()->filter(fn ($user) => User::isStaffRole($user->role))->values();
        Notification::send($staff, new ComplaintSubmitted(
            $customer,
            $subject,
            $description,
            $loanId,
            $priority
        ));
    }

    // ==================== COMBINED NOTIFICATIONS ====================

    /**
     * Handle loan application status change
     */
    public static function handleLoanStatusChange(LoanApplication $application, string $oldStatus, string $newStatus)
    {
        switch ($newStatus) {
            case 'approved':
                self::notifyLoanPendingFinalApproval($application);
                break;
            case 'rejected':
                self::notifyLoanRejected($application);
                break;
            case 'disbursed':
                // Disbursement notification is handled by LoanDisbursementController
                break;
        }
    }

    /**
     * Handle overdue repayments (notify both customer and staff)
     */
    public static function handleOverdueRepayment(
        LoanApplication $application,
        int $installmentNumber,
        float $amount,
        float $lateFee,
        int $daysOverdue
    ) {
        // Notify customer
        self::notifyCustomerLoanOverdue($application, $installmentNumber, $amount, $lateFee, $daysOverdue);
        
        // Notify staff
        self::notifyStaffRepaymentOverdue($application, $installmentNumber, $amount, $lateFee, $daysOverdue);
    }
}
