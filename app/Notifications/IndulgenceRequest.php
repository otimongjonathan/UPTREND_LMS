<?php

namespace App\Notifications;

use App\Models\LoanApplication;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IndulgenceRequest extends Notification
{
    use Queueable;

    public function __construct(
        public LoanApplication $application,
        public string $reason,
        public ?int $requestedExtensionDays = null
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Request for Indulgence - Loan #' . $this->application->id)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A customer has submitted a request for indulgence on their loan.')
            ->line('**Request Details:**')
            ->line('Customer: ' . $this->application->user->name)
            ->line('Loan ID: #' . $this->application->id)
            ->line('Loan Amount: UGX ' . number_format($this->application->amount, 2))
            ->line('Reason for Indulgence: ' . $this->reason);

        if ($this->requestedExtensionDays) {
            $message->line('Requested Extension: ' . $this->requestedExtensionDays . ' days');
        }

        return $message
            ->action('Review Request', url('/applications/' . $this->application->id))
            ->line('Please review this indulgence request and take appropriate action.');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'application_id' => $this->application->id,
            'customer_name' => $this->application->user->name,
            'reason' => $this->reason,
            'extension_days' => $this->requestedExtensionDays,
            'message' => 'Indulgence request from ' . $this->application->user->name . ' for loan #' . $this->application->id,
            'type' => 'indulgence_request',
        ];
    }
}
