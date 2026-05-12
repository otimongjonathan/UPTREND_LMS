<?php

namespace App\Notifications;

use App\Models\LoanApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanReschedulingRequest extends Notification
{
    use Queueable;

    public function __construct(
        public LoanApplication $application,
        public string $reason,
        public ?string $proposedDate = null
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Loan Rescheduling Request - Loan #' . $this->application->id)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A customer has requested to reschedule their loan repayment.')
            ->line('**Request Details:**')
            ->line('Customer: ' . $this->application->user->name)
            ->line('Loan ID: #' . $this->application->id)
            ->line('Original Amount: UGX ' . number_format($this->application->amount, 2))
            ->line('Reason: ' . $this->reason);

        if ($this->proposedDate) {
            $message->line('Proposed New Date: ' . $this->proposedDate);
        }

        return $message
            ->action('Review Request', url('/applications/' . $this->application->id))
            ->line('Please review and respond to this rescheduling request.');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'application_id' => $this->application->id,
            'customer_name' => $this->application->user->name,
            'reason' => $this->reason,
            'proposed_date' => $this->proposedDate,
            'message' => 'Loan rescheduling request from ' . $this->application->user->name,
            'type' => 'loan_rescheduling',
        ];
    }
}
