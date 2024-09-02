<?php

namespace App\Jobs;

use App\Mail\MessageNotificationMail;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class MessageNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $friendEmail;
    protected $attachmentPath;

    /**
     * Create a new job instance.
     */
    public function __construct($friendEmail, $attachmentPath = null)
    {
        $this->friendEmail = $friendEmail;
        $this->attachmentPath = $attachmentPath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->friendEmail)->send(new MessageNotificationMail($this->attachmentPath));
    }
}
