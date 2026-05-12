<?php

namespace App\Notifications;

use App\Models\LoanApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanApplicationPendingFinalApproval extends Notification
{
    use Queueable;

    public function __construct(public LoanApplication $application) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Loan Approved - Come for Final Approval - Application #' . $this->application->id)
            ->greeting('Congratulations ' . $notifiable->name . '!')
            ->line('Your loan application has been approved!')
            ->line('**Next Steps:**')
            ->line('Please visit our office for final approval and documentation.')
            ->line('**Approved Loan Details:**')
            ->line('Application ID: #' . $this->application->id)
            ->line('Amount: UGX ' . number_format($this->application->amount, 2))
            ->line('Term: ' . $this->application->term_months . ' months')
            ->line('**Required Documents:**')
            ->line('- Valid National ID')
            ->line('- Proof of Income')
            ->line('- Any collateral documents (if applicable)')
            ->action('View Application Details', url('/customer/applications/' . $this->application->id))
            ->line('Please contact us to schedule your final approval appointment.')
            ->line('Contact: +256 700 000 000');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'application_id' => $this->application->id,
            'amount' => $this->application->amount,
            'message' => 'Loan application #' . $this->application->id . ' approved. Please come for final approval.',
            'type' => 'pending_final_approval',
        ];
    }
}
