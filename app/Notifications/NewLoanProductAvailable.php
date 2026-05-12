<?php

namespace App\Notifications;

use App\Models\LoanProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewLoanProductAvailable extends Notification
{
    use Queueable;

    public function __construct(public LoanProduct $product) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Loan Product Available: ' . $this->product->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('We are excited to announce a new loan product that might interest you!')
            ->line('**Product Details:**')
            ->line('Name: ' . $this->product->name)
            ->line('Interest Rate: ' . $this->product->interest_rate . '% per year')
            ->line('Max Amount: UGX ' . number_format($this->product->max_amount, 2))
            ->line('Max Term: ' . $this->product->max_term_months . ' months')
            ->line('Description: ' . $this->product->description)
            ->action('Apply Now', url('/customer/my-loans/apply'))
            ->line('Don\'t miss this opportunity to achieve your financial goals!');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'interest_rate' => $this->product->interest_rate,
            'message' => 'New loan product available: ' . $this->product->name,
        ];
    }
}
