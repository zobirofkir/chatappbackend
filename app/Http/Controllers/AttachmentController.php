<?php

namespace App\Http\Controllers;

use App\Events\AttachmentUpdated;
use App\Http\Requests\AttachmentRequest;
use App\Http\Resources\AttachmentResource;
use App\Jobs\MessageNotificationJob;
use App\Models\Attachment;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : AnonymousResourceCollection
    {
        return AttachmentResource::collection(
            Attachment::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttachmentRequest $request, $conversation_id) : AttachmentResource
    {
        // Verify that the message exists within the specified conversation
        Conversation::where('id', $conversation_id)->first();

        $data = $request->validated();

        // Check if a file is uploaded
        if ($request->hasFile('file_path')) {
            // Store the file in the 'attachments' directory
            $filePath = $request->file('file_path')->store('attachments', 'public');
            $data['file_path'] = $filePath;
        }

        // Create the attachment
        $attachment = Attachment::create([
            'conversation_id' => $conversation_id,
            'file_path' => $data['file_path'] ?? null,
            'file_type' => $data['file_type'] ?? null,
        ]);

        // Dispatch event
        broadcast(new AttachmentUpdated($attachment));

        // Dispatch a job to handle additional tasks, like sending notifications
        MessageNotificationJob::dispatch($request->user()->email, $attachment->file_path);

        return AttachmentResource::make($attachment);
    }

    /**
     * Display the specified resource.
     */
    public function show($conversation_id, $attachment_id) : AttachmentResource
    {
        $attachment = Attachment::where('id', $attachment_id)->firstOrFail();
    
        return AttachmentResource::make($attachment);
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(AttachmentRequest $request, $conversation_id, $attachment_id) : AttachmentResource
    {
        $attachment = Attachment::findOrFail($attachment_id);
        $data = $request->validated();

        if ($request->hasFile('file_path')) {
            if ($attachment->file_path) {
                Storage::disk('public')->delete($attachment->file_path);
            }

            $data['file_path'] = $request->file('file_path')->store('attachments', 'public');
        }

        $attachment->update($data);

        broadcast(new AttachmentUpdated($attachment));

        return AttachmentResource::make(
            $attachment->refresh()
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($conversation_id, $attachment_id) : bool
    {    
        $attachment = Attachment::find($attachment_id);
            
        if ($attachment->file_path) {
            Storage::disk('public')->delete($attachment->file_path);
        }
    
        broadcast(new AttachmentUpdated($attachment));
        
        return $attachment->delete();        
    }
}
