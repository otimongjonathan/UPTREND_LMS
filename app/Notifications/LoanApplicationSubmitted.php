<?php

namespace App\Notifications;

use App\Models\LoanApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanApplicationSubmitted extends Notification
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
            ->subject('New Loan Application Submitted - #' . $this->application->id)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new loan application has been submitted and requires your review.')
            ->line('**Application Details:**')
            ->line('Applicant: ' . $this->application->applicant_full_name)
            ->line('Amount: UGX ' . number_format($this->application->amount, 2))
            ->line('Purpose: ' . $this->application->loan_purpose)
            ->action('Review Application', url('/applications/' . $this->application->id))
            ->line('Please review and process this application at your earliest convenience.');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'application_id' => $this->application->id,
            'applicant_name' => $this->application->applicant_full_name,
            'amount' => $this->application->amount,
            'message' => 'New loan application submitted by ' . $this->application->applicant_full_name,
        ];
    }
}
