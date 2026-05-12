<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ComplaintSubmitted extends Notification
{
    use Queueable;

    public function __construct(
        public User $customer,
        public string $subject,
        public string $description,
        public ?int $loanId = null,
        public string $priority = 'normal'
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('New Customer Complaint - ' . strtoupper($this->priority) . ' Priority')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new complaint has been submitted by a customer.')
            ->line('**Complaint Details:**')
            ->line('Customer: ' . $this->customer->name)
            ->line('Email: ' . $this->customer->email)
            ->line('Subject: ' . $this->subject)
            ->line('Priority: ' . strtoupper($this->priority));

        if ($this->loanId) {
            $message->line('Related Loan: #' . $this->loanId);
        }

        return $message
            ->line('**Description:**')
            ->line($this->description)
            ->action('View Complaint', url('/complaints'))
            ->line('Please address this complaint promptly.');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'customer_id' => $this->customer->id,
            'customer_name' => $this->customer->name,
            'subject' => $this->subject,
            'description' => $this->description,
            'loan_id' => $this->loanId,
            'priority' => $this->priority,
            'message' => 'New complaint from ' . $this->customer->name . ': ' . $this->subject,
            'type' => 'complaint',
        ];
    }
}
