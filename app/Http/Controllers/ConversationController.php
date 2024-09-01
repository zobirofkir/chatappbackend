<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConversationRequest;
use App\Http\Resources\ConversationResource;
use App\Models\Conversation;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ConversationResource::collection(
            Conversation::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ConversationRequest $request)
    {
        return ConversationResource::make(
            Conversation::create(array_merge(
                ["user_id" => $request->user()->id],
                $request->validated()
            ))
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Conversation $conversation)
    {
        return ConversationResource::make($conversation);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ConversationRequest $request, Conversation $conversation)
    {
        $conversation->update($request->validated());
        return ConversationResource::make(
            $conversation->refresh()
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Conversation $conversation)
    {
        return $conversation->delete();
    }


    /**
     * Search for a conversation
     *
     * @param [type] $conversation
     * @return void
     */
    public function search($conversation)
    {
        $conversations = Conversation::where('name', $conversation)->get();    
        return ConversationResource::collection(
            $conversations
        );
    }
}
