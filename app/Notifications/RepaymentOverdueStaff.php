<?php

namespace App\Notifications;

use App\Models\LoanApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RepaymentOverdueStaff extends Notification
{
    use Queueable;

    public function __construct(
        public LoanApplication $application,
        public int $installmentNumber,
        public float $amount,
        public float $lateFee,
        public int $daysOverdue
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('ALERT: Overdue Loan Repayment - Loan #' . $this->application->id)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A loan repayment is now overdue and requires attention.')
            ->line('**Overdue Loan Details:**')
            ->line('Customer: ' . $this->application->user->name)
            ->line('Loan ID: #' . $this->application->id)
            ->line('Installment: #' . $this->installmentNumber)
            ->line('Original Amount: UGX ' . number_format($this->amount, 2))
            ->line('Late Fee: UGX ' . number_format($this->lateFee, 2))
            ->line('Total Due: UGX ' . number_format($this->amount + $this->lateFee, 2))
            ->line('Days Overdue: ' . $this->daysOverdue . ' days')
            ->action('View Loan Details', url('/applications/' . $this->application->id))
            ->line('Please follow up with the customer immediately.');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'application_id' => $this->application->id,
            'customer_name' => $this->application->user->name,
            'installment_number' => $this->installmentNumber,
            'amount' => $this->amount,
            'late_fee' => $this->lateFee,
            'days_overdue' => $this->daysOverdue,
            'message' => 'Overdue payment: ' . $this->application->user->name . ' - UGX ' . number_format($this->amount + $this->lateFee, 2) . ' (' . $this->daysOverdue . ' days)',
            'type' => 'repayment_overdue',
        ];
    }
}
