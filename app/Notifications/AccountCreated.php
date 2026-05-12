<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountCreated extends Notification
{
    use Queueable;

    public function __construct(public User $user) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to UPTREND LMS - Account Created Successfully')
            ->greeting('Welcome ' . $notifiable->name . '!')
            ->line('Your account has been successfully created at UPTREND LMS.')
            ->line('**Account Details:**')
            ->line('Name: ' . $notifiable->name)
            ->line('Email: ' . $notifiable->email)
            ->line('Business: ' . $notifiable->business_name)
            ->line('Account Type: ' . ucfirst($notifiable->role))
            ->action('Login to Your Account', url('/customer/login'))
            ->line('You can now browse loan products and submit loan applications.')
            ->line('Thank you for choosing UPTREND LMS!');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'user_id' => $notifiable->id,
            'message' => 'Welcome to UPTREND LMS! Your account has been created successfully.',
            'type' => 'account_created',
        ];
    }
}
