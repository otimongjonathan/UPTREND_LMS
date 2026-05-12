<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RepaymentDueReminder extends Notification
{
    use Queueable;

    public function __construct(
        public int $loanId,
        public int $installmentNumber,
        public float $amount,
        public string $dueDate,
        public int $daysUntilDue
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $urgency = $this->daysUntilDue <= 3 ? 'Urgent: ' : '';
        
        return (new MailMessage)
            ->subject($urgency . 'Loan Repayment Due - Installment #' . $this->installmentNumber)
            ->greeting('Hello ' . $notifiable->name)
            ->line('This is a reminder that your loan repayment is due soon.')
            ->line('**Payment Details:**')
            ->line('Loan ID: #' . $this->loanId)
            ->line('Installment: #' . $this->installmentNumber)
            ->line('Amount Due: UGX ' . number_format($this->amount, 2))
            ->line('Due Date: ' . $this->dueDate)
            ->line('Days Until Due: ' . $this->daysUntilDue . ' days')
            ->action('View Repayment Schedule', url('/customer/my-loans/' . $this->loanId))
            ->line('Please ensure timely payment to avoid late fees and maintain your credit score.');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'loan_id' => $this->loanId,
            'installment_number' => $this->installmentNumber,
            'amount' => $this->amount,
            'due_date' => $this->dueDate,
            'message' => 'Repayment due in ' . $this->daysUntilDue . ' days: UGX ' . number_format($this->amount, 2),
        ];
    }
}
