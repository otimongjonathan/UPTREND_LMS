<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    protected $signature = 'email:test {recipient}';
    protected $description = 'Send a test email to verify configuration';

    public function handle()
    {
        $recipient = $this->argument('recipient');
        
        $this->info('Sending test email to: ' . $recipient);
        
        try {
            Mail::raw('🎉 Congratulations! Your UPTREND LMS email system is working perfectly!', function($message) use ($recipient) {
                $message->to($recipient)
                        ->subject('✅ UPTREND LMS - Email Test Successful');
            });
            
            $this->info('✅ Email sent successfully!');
            $this->info('Check your inbox at: ' . $recipient);
            
        } catch (\Exception $e) {
            $this->error('❌ Failed to send email: ' . $e->getMessage());
            $this->error('Please check your .env configuration');
        }
        
        return 0;
    }
}
