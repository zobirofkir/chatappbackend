<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttachmentRequest extends FormRequest
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
            "file_path" => "nullable|image|mimes:jpg,jpeg,png,pdf|max:2048",
            "file_type" => "nullable|string"
        ];
    }
}
