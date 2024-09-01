<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Requests\MessageRequest;
use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
use App\Jobs\MessageNotificationJob;
use App\Mail\MessageNotificationMail;
use App\Models\Attachment;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return MessageResource::collection(
            Message::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MessageRequest $request, $conversation_id)
    {
        // Create and store the message
        $message = Message::create(
            array_merge(
                [
                    "user_id" => $request->user()->id,
                    "conversation_id" => $conversation_id
                ],
                $request->validated()
            )
        );
    
        $attachmentPath = null;
    
        /**
         * Check if a file is uploaded
         */
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $attachmentPath = $file->store('attachments', 'public');
            $fileType = $file->getClientOriginalExtension();
    
            // Create attachment record
            Attachment::create([
                'message_id' => $message->id,
                'file_path' => $attachmentPath,
                'file_type' => $fileType
            ]);
        }
    
        /**
         * Get the recipient user for the conversation
         */
        $recipient = $this->getRecipientUserForConversation($conversation_id);
        $friendUserId = $recipient->id;
        $friendEmail = $recipient->email;
    
        Notification::create([
            'user_id' => $friendUserId,
            'message_id' => $message->id,
            'notification_type' => 'message',
            'status' => 'unread'
        ]);
    
        /**
         * Send email
         */
        MessageNotificationJob::dispatch($friendEmail, $message, $attachmentPath);

        /**
         * Send push notification
         */
        broadcast(new MessageSent($message));
        return MessageResource::make($message);
    }
    
    /**
     * Display the specified resource.
     */
    public function show($conversation_id, $message_id)
    {
        /**
         * Get the message
         */
        $message = Message::where('conversation_id', $conversation_id)
                           ->where('id', $message_id)
                           ->firstOrFail();
        return MessageResource::make($message);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MessageRequest $request, $conversation_id, $message_id)
    {
        /**
         * Get the message
         */
        $message = Message::where('conversation_id', $conversation_id)
                           ->where('id', $message_id)
                           ->firstOrFail();
                           
        /**
         * Update the message
         */
        $message->update($request->validated());

        broadcast(new MessageSent($message));
        return MessageResource::make(
            $message->refresh()
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($conversation_id, $message_id)
    {
        /**
         * Get the message
         */
        $message = Message::where('conversation_id', $conversation_id)
                           ->where('id', $message_id)
                           ->firstOrFail();

        /**
         * Delete the message
         */
        $message->delete();
        broadcast(new MessageSent($message));
        return $message->delete();
    }
    
    /**
     * Get the recipient user for the conversation
     */
    private function getRecipientUserForConversation($conversation_id)
    {
        /**
         * Get the conversation
         */
        $conversation = Conversation::find($conversation_id);
    
        if (!$conversation) {
            return null;
        }
    
        return User::where('id', '!=', Auth::id())->first();
    }

}
