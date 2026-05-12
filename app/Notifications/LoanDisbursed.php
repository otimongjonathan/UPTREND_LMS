<?php

namespace App\Notifications;

use App\Models\LoanDisbursement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanDisbursed extends Notification
{
    use Queueable;

    public function __construct(public LoanDisbursement $disbursement) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Loan Disbursed - UGX ' . number_format($this->disbursement->disbursed_amount, 2))
            ->greeting('Great News ' . $notifiable->name . '!')
            ->line('Your loan has been successfully disbursed!')
            ->line('**Disbursement Details:**')
            ->line('Amount: UGX ' . number_format($this->disbursement->disbursed_amount, 2))
            ->line('Method: ' . ucfirst($this->disbursement->disbursement_method))
            ->line('Date: ' . $this->disbursement->disbursement_date->format('M d, Y'))
            ->line('Reference: ' . $this->disbursement->transaction_reference)
            ->action('View Repayment Schedule', url('/customer/my-loans/' . $this->disbursement->loan_application_id))
            ->line('Please check your account for the funds.')
            ->line('Remember to make timely repayments to maintain a good credit score.');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'disbursement_id' => $this->disbursement->id,
            'loan_id' => $this->disbursement->loan_application_id,
            'amount' => $this->disbursement->disbursed_amount,
            'message' => 'Loan disbursed: UGX ' . number_format($this->disbursement->disbursed_amount, 2),
        ];
    }
}
