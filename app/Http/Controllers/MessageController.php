<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
use App\Models\Message;


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
        return MessageResource::make(
            Message::create(
                array_merge(
                    [
                        "user_id" => $request->user()->id,
                        "conversation_id" => $conversation_id
                    ],
                    $request->validated()
                )
            )
        );
    }
        
    /**
     * Display the specified resource.
     */
    public function show($conversation_id, $message_id)
    {
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
        $message = Message::where('conversation_id', $conversation_id)
                           ->where('id', $message_id)
                           ->firstOrFail();
                           
        $message->update($request->validated());
        return MessageResource::make(
            $message->refresh()
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($conversation_id, $message_id)
    {
        $message = Message::where('conversation_id', $conversation_id)
                           ->where('id', $message_id)
                           ->firstOrFail();
    
        return $message->delete();
    }
    
}
