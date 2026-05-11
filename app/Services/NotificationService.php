<?php

namespace App\Services;

use App\Models\LoanApplication;
use App\Models\Repayment;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Send loan application status notification
     */
    public static function notifyApplicationStatus(LoanApplication $loan, $status)
    {
        $user = $loan->user;
        
        $message = "Your loan application #{$loan->id} has been {$status}.";
        
        if ($status === 'approved') {
            $message .= " Your loan amount of UGX " . number_format($loan->amount) . 
                       " has been approved. Please log in to view details.";
        } elseif ($status === 'rejected') {
            $message .= " Unfortunately, your application could not be approved at this time.";
        }
        
        // Queue email notification
        self::sendEmailNotification($user, "Loan Application {$status}", $message);
        
        // Log notification
        self::logNotification($user->id, 'loan_application', $loan->id, $message);
    }

    /**
     * Send repayment reminder notification
     */
    public static function sendRepaymentReminder(Repayment $repayment)
    {
        $loan = $repayment->loanApplication;
        $user = $loan->user;
        
        $daysUntilDue = now()->diffInDays($repayment->due_date);
        
        if ($daysUntilDue > 0) {
            $message = "Reminder: Your loan repayment of UGX " . number_format($repayment->amount) . 
                      " is due in {$daysUntilDue} days on " . $repayment->due_date->format('M d, Y') . ".";
        } else {
            $message = "URGENT: Your loan repayment of UGX " . number_format($repayment->amount) . 
                      " is overdue. Please make the payment immediately.";
        }
        
        self::sendEmailNotification($user, "Loan Repayment Reminder", $message);
        self::logNotification($user->id, 'repayment_reminder', $repayment->id, $message);
    }

    /**
     * Send payment received notification
     */
    public static function notifyPaymentReceived(Repayment $repayment, $amount)
    {
        $loan = $repayment->loanApplication;
        $user = $loan->user;
        
        $message = "Your payment of UGX " . number_format($amount) . 
                  " for loan #{$loan->id} has been received and processed. Thank you.";
        
        self::sendEmailNotification($user, "Payment Received", $message);
        self::logNotification($user->id, 'payment_received', $repayment->id, $message);
    }

    /**
     * Send overdue payment notice
     */
    public static function sendOverdueNotice(Repayment $repayment)
    {
        $loan = $repayment->loanApplication;
        $user = $loan->user;
        
        $daysOverdue = now()->diffInDays($repayment->due_date, false);
        
        $message = "NOTICE: Your loan repayment of UGX " . number_format($repayment->amount) . 
                  " is {$daysOverdue} days overdue. Late payment penalties may apply. " .
                  "Please contact us immediately.";
        
        self::sendEmailNotification($user, "URGENT: Overdue Payment Notice", $message);
        self::logNotification($user->id, 'overdue_notice', $repayment->id, $message);
    }

    /**
     * Send loan disbursement notification
     */
    public static function notifyDisbursement(LoanApplication $loan, $amount)
    {
        $user = $loan->user;
        
        $message = "Your loan of UGX " . number_format($amount) . 
                  " has been successfully disbursed to your account. " .
                  "You will receive SMS and email confirmation shortly.";
        
        self::sendEmailNotification($user, "Loan Disbursement Confirmation", $message);
        self::logNotification($user->id, 'loan_disbursement', $loan->id, $message);
    }

    /**
     * Send email notification
     */
    private static function sendEmailNotification(User $user, $subject, $message)
    {
        try {
            // Queue email asynchronously
            // Mail::to($user->email)->queue(new NotificationMail($subject, $message));
            
            // For now, just log it
            \Log::info("Email notification to {$user->email}: {$subject}");
        } catch (\Exception $e) {
            \Log::error("Failed to send email: " . $e->getMessage());
        }
    }

    /**
     * Log notification in database
     */
    private static function logNotification($userId, $type, $relatedId, $message)
    {
        \DB::table('notifications')->insert([
            'user_id' => $userId,
            'notification_type' => $type,
            'related_id' => $relatedId,
            'message' => $message,
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
