<?php

namespace App\Http\Controllers;

use App\Services\RepaymentNotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function sendReminders()
    {
        $notificationService = app(RepaymentNotificationService::class);
        $results = $notificationService->sendRepaymentReminders();
        
        $message = "Notifications sent: " . 
                  "Due Today: {$results['due_today']}, " .
                  "Due Tomorrow: {$results['due_tomorrow']}, " .
                  "Due in 3 Days: {$results['due_in_3_days']}, " .
                  "Overdue: {$results['overdue']}";
        
        return redirect()->back()->with('success', $message);
    }
    
    public function testNotification(Request $request)
    {
        $loanId = $request->input('loan_id');
        
        if (!$loanId) {
            return redirect()->back()->with('error', 'Please provide a loan ID');
        }
        
        $loan = \App\Models\LoanApplication::find($loanId);
        
        if (!$loan) {
            return redirect()->back()->with('error', 'Loan not found');
        }
        
        $notificationService = app(RepaymentNotificationService::class);
        $notificationService->sendLoanIssuedNotification($loan);
        
        return redirect()->back()->with('success', "Test notification sent for Loan #{$loanId}");
    }
}