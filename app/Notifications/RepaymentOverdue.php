<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RepaymentOverdue extends Notification
{
    use Queueable;

    public function __construct(
        public int $loanId,
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
            ->subject('URGENT: Overdue Loan Repayment - Installment #' . $this->installmentNumber)
            ->greeting('Hello ' . $notifiable->name)
            ->line('Your loan repayment is now overdue.')
            ->line('**Overdue Payment Details:**')
            ->line('Loan ID: #' . $this->loanId)
            ->line('Installment: #' . $this->installmentNumber)
            ->line('Original Amount: UGX ' . number_format($this->amount, 2))
            ->line('Late Fee: UGX ' . number_format($this->lateFee, 2))
            ->line('Total Due: UGX ' . number_format($this->amount + $this->lateFee, 2))
            ->line('Days Overdue: ' . $this->daysOverdue . ' days')
            ->action('Make Payment Now', url('/customer/my-loans/' . $this->loanId))
            ->line('Please make payment immediately to avoid additional penalties and credit score impact.');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'loan_id' => $this->loanId,
            'installment_number' => $this->installmentNumber,
            'amount' => $this->amount,
            'late_fee' => $this->lateFee,
            'days_overdue' => $this->daysOverdue,
            'message' => 'Overdue payment: UGX ' . number_format($this->amount + $this->lateFee, 2) . ' (' . $this->daysOverdue . ' days)',
        ];
    }
}
