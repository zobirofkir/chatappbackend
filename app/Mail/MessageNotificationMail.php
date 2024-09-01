<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;

class MessageNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $message;
    public $attachmentPath;

    /**
     * Create a new message instance.
     */
    public function __construct($message, $attachmentPath = null)
    {
        $this->message = $message;
        $this->attachmentPath = $attachmentPath;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $email = $this->view('emails.message_notification')
                      ->with([
                          'messageContent' => $this->message->message,
                      ]);

        if ($this->attachmentPath) {
            $attachmentFullPath = storage_path('app/public/attachments/' . $this->attachmentPath);

            if (file_exists($attachmentFullPath)) {
                $email->attach($attachmentFullPath);
            }
        }

        return $email;
    }
}
