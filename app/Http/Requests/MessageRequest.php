<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "conversation_id" => "nullable|exists:conversations,id",
            "message" => "required|string",
            "message_type" => "nullable|string",
            "status" => "nullable|string",
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ];
    }
}
