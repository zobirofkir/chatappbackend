<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class ResetPassword extends Notification
{
    public $token;
    public $status; // Add this to include status messages

    public function __construct($token, $status = null)
    {
        $this->token = $token;
        $this->status = $status; // Initialize status
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject(Lang::get('Reset Password Notification'))
                    ->view('emails.reset-password', [
                        'token' => $this->token,
                        'notifiable' => $notifiable,
                        'status' => $this->status, 
                    ]);
    }
}
