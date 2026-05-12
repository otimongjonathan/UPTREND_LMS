<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RepaymentReceived extends Notification
{
    use Queueable;

    public function __construct(
        public int $loanId,
        public int $installmentNumber,
        public float $amount,
        public float $remainingBalance,
        public string $paymentDate
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Payment Received - Installment #' . $this->installmentNumber)
            ->greeting('Thank you ' . $notifiable->name . '!')
            ->line('We have received your loan repayment.')
            ->line('**Payment Details:**')
            ->line('Loan ID: #' . $this->loanId)
            ->line('Installment: #' . $this->installmentNumber)
            ->line('Amount Paid: UGX ' . number_format($this->amount, 2))
            ->line('Payment Date: ' . $this->paymentDate)
            ->line('Remaining Balance: UGX ' . number_format($this->remainingBalance, 2))
            ->action('View Loan Details', url('/customer/my-loans/' . $this->loanId))
            ->line('Thank you for your timely payment!');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'loan_id' => $this->loanId,
            'installment_number' => $this->installmentNumber,
            'amount' => $this->amount,
            'remaining_balance' => $this->remainingBalance,
            'message' => 'Payment received: UGX ' . number_format($this->amount, 2),
        ];
    }
}
