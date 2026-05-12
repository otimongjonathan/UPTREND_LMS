<?php

namespace App\Notifications;

use App\Models\LoanApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanApplicationApproved extends Notification
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
            ->subject('Loan Application Approved - #' . $this->application->id)
            ->greeting('Congratulations ' . $notifiable->name . '!')
            ->line('Your loan application has been approved!')
            ->line('**Loan Details:**')
            ->line('Amount: UGX ' . number_format($this->application->amount, 2))
            ->line('Interest Rate: ' . $this->application->interest_rate . '%')
            ->line('Term: ' . $this->application->loan_term_months . ' months')
            ->action('View Loan Details', url('/customer/my-loans/' . $this->application->id))
            ->line('The funds will be disbursed to your account shortly.')
            ->line('Thank you for choosing UPTREND LMS!');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'application_id' => $this->application->id,
            'amount' => $this->application->amount,
            'message' => 'Your loan application #' . $this->application->id . ' has been approved',
        ];
    }
}
