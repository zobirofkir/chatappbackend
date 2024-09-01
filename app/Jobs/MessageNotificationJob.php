<?php

namespace App\Jobs;

use App\Mail\MessageNotificationMail;
use App\Models\Message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class MessageNotificationJob implements ShouldQueue
{
    use Queueable;

    private $friendEmail;
    private $message;


    /**
     * Create a new job instance.
     */
    public function __construct($friendEmail, Message $message)
    {
        $this->friendEmail = $friendEmail;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->friendEmail)->send(new MessageNotificationMail($this->message));
    }
}
