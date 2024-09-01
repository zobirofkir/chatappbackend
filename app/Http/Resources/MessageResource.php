<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "conversation_id" => ConversationResource::make($this->conversation)->id,
            "user_id" => UserResource::make($this->user)->id,
            "message" => $this->message,
            "message_type" => $this->message_type,
            "status" => $this->status,
        ];
    }
}
