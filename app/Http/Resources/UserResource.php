<?php

namespace App\Http\Resources;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            "name" => $this->name,
            "email" => $this->email,
            "image" => $this->image ? asset('storage/' . ltrim($this->image, '/')) : null
        ];
    }
}
