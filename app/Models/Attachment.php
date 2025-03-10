<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        "conversation_id",
        "file_path",
        "file_type"
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}
