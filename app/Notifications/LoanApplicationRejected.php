<?php

namespace App\Notifications;

use App\Models\LoanApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanApplicationRejected extends Notification
{
    use Queueable;

    public function __construct(public LoanApplication $application, public ?string $reason = null) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Loan Application Update - #' . $this->application->id)
            ->greeting('Hello ' . $notifiable->name)
            ->line('We regret to inform you that your loan application has not been approved at this time.');

        if ($this->reason) {
            $mail->line('**Reason:** ' . $this->reason);
        }

        return $mail
            ->line('You may reapply after addressing the concerns or contact us for more information.')
            ->action('View Application', url('/customer/my-loans/' . $this->application->id))
            ->line('Thank you for your interest in UPTREND LMS.');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'application_id' => $this->application->id,
            'reason' => $this->reason,
            'message' => 'Your loan application #' . $this->application->id . ' was not approved',
        ];
    }
}
