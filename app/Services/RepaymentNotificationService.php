<?php

namespace App\Services;

use App\Models\LoanApplication;
use App\Models\Repayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RepaymentNotificationService
{
    /**
     * Send loan issuance notification to customer
     */
    public function sendLoanIssuedNotification(LoanApplication $loan): void
    {
        $customer = $loan->user;
        $firstPayment = $loan->repayments()->orderBy('installment_number')->first();
        
        $message = $this->generateLoanIssuedMessage($loan, $firstPayment);
        
        // Log notification (in production, send SMS/Email)
        Log::info("Loan Issued Notification", [
            'customer_id' => $customer->id,
            'customer_name' => $customer->name,
            'customer_phone' => $customer->phone,
            'loan_id' => $loan->id,
            'message' => $message
        ]);
        
        // Store in notifications table
        $this->storeNotification($customer, 'loan_issued', $message, $loan->id);
        
        // In production, integrate with SMS/Email service
        // $this->sendSMS($customer->phone, $message);
        // $this->sendEmail($customer->email, 'Loan Approved & Issued', $message);
    }
    
    /**
     * Send repayment reminder notifications
     */
    public function sendRepaymentReminders(): array
    {
        $results = [
            'due_today' => 0,
            'due_tomorrow' => 0,
            'due_in_3_days' => 0,
            'overdue' => 0
        ];
        
        // Due today
        $dueToday = Repayment::with('loanApplication.user')
            ->where('status', 'pending')
            ->whereDate('due_date', today())
            ->get();
            
        foreach ($dueToday as $repayment) {
            $this->sendDueTodayNotification($repayment);
            $results['due_today']++;
        }
        
        // Due tomorrow
        $dueTomorrow = Repayment::with('loanApplication.user')
            ->where('status', 'pending')
            ->whereDate('due_date', today()->addDay())
            ->get();
            
        foreach ($dueTomorrow as $repayment) {
            $this->sendDueTomorrowNotification($repayment);
            $results['due_tomorrow']++;
        }
        
        // Due in 3 days
        $dueIn3Days = Repayment::with('loanApplication.user')
            ->where('status', 'pending')
            ->whereDate('due_date', today()->addDays(3))
            ->get();
            
        foreach ($dueIn3Days as $repayment) {
            $this->sendDueIn3DaysNotification($repayment);
            $results['due_in_3_days']++;
        }
        
        // Overdue
        $overdue = Repayment::with('loanApplication.user')
            ->where('status', '!=', 'completed')
            ->where('due_date', '<', today())
            ->get();
            
        foreach ($overdue as $repayment) {
            $this->sendOverdueNotification($repayment);
            $results['overdue']++;
        }
        
        return $results;
    }
    
    /**
     * Send payment confirmation notification
     */
    public function sendPaymentConfirmation(Repayment $repayment): void
    {
        $customer = $repayment->loanApplication->user;
        $nextPayment = Repayment::where('loan_application_id', $repayment->loan_application_id)
            ->where('installment_number', '>', $repayment->installment_number)
            ->where('status', 'pending')
            ->orderBy('installment_number')
            ->first();
            
        $message = $this->generatePaymentConfirmationMessage($repayment, $nextPayment);
        
        Log::info("Payment Confirmation", [
            'customer_id' => $customer->id,
            'repayment_id' => $repayment->id,
            'message' => $message
        ]);
        
        $this->storeNotification($customer, 'payment_confirmed', $message, $repayment->loan_application_id);
    }
    
    /**
     * Generate loan issued message
     */
    private function generateLoanIssuedMessage(LoanApplication $loan, ?Repayment $firstPayment): string
    {
        $message = "🎉 LOAN APPROVED & ISSUED\n\n";
        $message .= "Dear {$loan->user->name},\n\n";
        $message .= "Your loan has been successfully issued!\n\n";
        $message .= "📋 LOAN DETAILS:\n";
        $message .= "• Loan ID: #{$loan->id}\n";
        $message .= "• Amount: UGX " . number_format($loan->amount, 2) . "\n";
        $message .= "• Interest Rate: {$loan->applied_interest_rate}% per annum\n";
        $message .= "• Term: {$loan->actual_term_months} months\n";
        $message .= "• Total Repayable: UGX " . number_format($loan->total_repayable, 2) . "\n\n";
        
        if ($firstPayment) {
            $message .= "💳 FIRST PAYMENT:\n";
            $message .= "• Amount: UGX " . number_format($firstPayment->amount, 2) . "\n";
            $message .= "• Due Date: " . Carbon::parse($firstPayment->due_date)->format('M d, Y') . "\n";
            $message .= "• Payment Frequency: " . ucfirst(str_replace('_', ' ', $firstPayment->payment_frequency)) . "\n\n";
        }
        
        $message .= "📱 You will receive reminders before each payment due date.\n\n";
        $message .= "Thank you for choosing UPTREND LMS!";
        
        return $message;
    }
    
    /**
     * Send due today notification
     */
    private function sendDueTodayNotification(Repayment $repayment): void
    {
        $customer = $repayment->loanApplication->user;
        
        $message = "⚠️ PAYMENT DUE TODAY\n\n";
        $message .= "Dear {$customer->name},\n\n";
        $message .= "Your loan payment is due TODAY!\n\n";
        $message .= "💳 PAYMENT DETAILS:\n";
        $message .= "• Loan ID: #{$repayment->loan_application_id}\n";
        $message .= "• Installment: #{$repayment->installment_number}\n";
        $message .= "• Amount Due: UGX " . number_format($repayment->amount, 2) . "\n";
        $message .= "• Due Date: TODAY (" . Carbon::parse($repayment->due_date)->format('M d, Y') . ")\n";
        $message .= "• Remaining Balance: UGX " . number_format($repayment->remaining_balance, 2) . "\n\n";
        $message .= "Please make your payment today to avoid late fees.\n\n";
        $message .= "Contact us for payment assistance.";
        
        Log::info("Due Today Notification", [
            'customer_id' => $customer->id,
            'repayment_id' => $repayment->id,
            'message' => $message
        ]);
        
        $this->storeNotification($customer, 'payment_due_today', $message, $repayment->loan_application_id);
    }
    
    /**
     * Send due tomorrow notification
     */
    private function sendDueTomorrowNotification(Repayment $repayment): void
    {
        $customer = $repayment->loanApplication->user;
        
        $message = "📅 PAYMENT DUE TOMORROW\n\n";
        $message .= "Dear {$customer->name},\n\n";
        $message .= "Friendly reminder: Your loan payment is due TOMORROW.\n\n";
        $message .= "💳 PAYMENT DETAILS:\n";
        $message .= "• Loan ID: #{$repayment->loan_application_id}\n";
        $message .= "• Installment: #{$repayment->installment_number}\n";
        $message .= "• Amount Due: UGX " . number_format($repayment->amount, 2) . "\n";
        $message .= "• Due Date: " . Carbon::parse($repayment->due_date)->format('M d, Y') . "\n\n";
        $message .= "Please prepare your payment to avoid any delays.";
        
        Log::info("Due Tomorrow Notification", [
            'customer_id' => $customer->id,
            'repayment_id' => $repayment->id,
            'message' => $message
        ]);
        
        $this->storeNotification($customer, 'payment_due_tomorrow', $message, $repayment->loan_application_id);
    }
    
    /**
     * Send due in 3 days notification
     */
    private function sendDueIn3DaysNotification(Repayment $repayment): void
    {
        $customer = $repayment->loanApplication->user;
        
        $message = "📋 UPCOMING PAYMENT\n\n";
        $message .= "Dear {$customer->name},\n\n";
        $message .= "Your next loan payment is due in 3 days.\n\n";
        $message .= "💳 PAYMENT DETAILS:\n";
        $message .= "• Loan ID: #{$repayment->loan_application_id}\n";
        $message .= "• Installment: #{$repayment->installment_number}\n";
        $message .= "• Amount Due: UGX " . number_format($repayment->amount, 2) . "\n";
        $message .= "• Due Date: " . Carbon::parse($repayment->due_date)->format('M d, Y') . "\n\n";
        $message .= "Plan ahead to ensure timely payment.";
        
        Log::info("Due In 3 Days Notification", [
            'customer_id' => $customer->id,
            'repayment_id' => $repayment->id,
            'message' => $message
        ]);
        
        $this->storeNotification($customer, 'payment_due_soon', $message, $repayment->loan_application_id);
    }
    
    /**
     * Send overdue notification
     */
    private function sendOverdueNotification(Repayment $repayment): void
    {
        $customer = $repayment->loanApplication->user;
        $daysOverdue = Carbon::parse($repayment->due_date)->diffInDays(now());
        
        $message = "🚨 PAYMENT OVERDUE\n\n";
        $message .= "Dear {$customer->name},\n\n";
        $message .= "Your loan payment is OVERDUE by {$daysOverdue} days.\n\n";
        $message .= "💳 OVERDUE PAYMENT:\n";
        $message .= "• Loan ID: #{$repayment->loan_application_id}\n";
        $message .= "• Installment: #{$repayment->installment_number}\n";
        $message .= "• Amount Due: UGX " . number_format($repayment->amount, 2) . "\n";
        $message .= "• Original Due Date: " . Carbon::parse($repayment->due_date)->format('M d, Y') . "\n";
        $message .= "• Days Overdue: {$daysOverdue} days\n\n";
        $message .= "⚠️ Late fees may apply. Please contact us immediately to arrange payment.";
        
        Log::info("Overdue Notification", [
            'customer_id' => $customer->id,
            'repayment_id' => $repayment->id,
            'days_overdue' => $daysOverdue,
            'message' => $message
        ]);
        
        $this->storeNotification($customer, 'payment_overdue', $message, $repayment->loan_application_id);
    }
    
    /**
     * Generate payment confirmation message
     */
    private function generatePaymentConfirmationMessage(Repayment $repayment, ?Repayment $nextPayment): string
    {
        $customer = $repayment->loanApplication->user;
        
        $message = "✅ PAYMENT CONFIRMED\n\n";
        $message .= "Dear {$customer->name},\n\n";
        $message .= "Your payment has been successfully received!\n\n";
        $message .= "💳 PAYMENT DETAILS:\n";
        $message .= "• Loan ID: #{$repayment->loan_application_id}\n";
        $message .= "• Installment: #{$repayment->installment_number}\n";
        $message .= "• Amount Paid: UGX " . number_format($repayment->paid_amount, 2) . "\n";
        $message .= "• Payment Date: " . Carbon::parse($repayment->paid_date)->format('M d, Y') . "\n";
        $message .= "• Remaining Loan Balance: UGX " . number_format($repayment->remaining_balance, 2) . "\n\n";
        
        if ($nextPayment) {
            $message .= "📅 NEXT PAYMENT:\n";
            $message .= "• Installment: #{$nextPayment->installment_number}\n";
            $message .= "• Amount Due: UGX " . number_format($nextPayment->amount, 2) . "\n";
            $message .= "• Due Date: " . Carbon::parse($nextPayment->due_date)->format('M d, Y') . "\n\n";
        } else {
            $message .= "🎉 CONGRATULATIONS! You have completed all loan payments!\n\n";
        }
        
        $message .= "Thank you for your timely payment!";
        
        return $message;
    }
    
    /**
     * Store notification in database
     */
    private function storeNotification(User $user, string $type, string $message, int $loanId): void
    {
        // Create notification record
        \DB::table('notifications')->insert([
            'user_id' => $user->id,
            'notification_type' => $type,
            'related_id' => $loanId,
            'message' => $message,
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
