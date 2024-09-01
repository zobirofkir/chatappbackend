<?php

namespace App\Http\Controllers;

use App\Events\AttachmentUpdated;
use App\Http\Requests\AttachmentRequest;
use App\Http\Resources\AttachmentResource;
use App\Jobs\MessageNotificationJob;
use App\Mail\MessageNotificationMail;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return AttachmentResource::collection(
            Attachment::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttachmentRequest $request, $message_id)
    {
        $data = $request->validated();

        // Check if a file is uploaded
        if ($request->hasFile('file_path')) {
            // Store the file in the 'attachments' directory
            $filePath = $request->file('file_path')->store('attachments', 'public');
            $data['file_path'] = $filePath;
        }

        // Create the attachment
        $attachment = Attachment::create(
            array_merge(
                ["message_id" => $message_id],
                $data
            )
        );

        // Dispatch event
        broadcast(new AttachmentUpdated($attachment));
        // Dispatch a job to handle additional tasks, like sending notifications
        MessageNotificationJob::dispatch($request->user()->email, $attachment->message, $attachment->file_path);

        return AttachmentResource::make($attachment);
    }

    /**
     * Display the specified resource.
     */
    public function show($attachment_id)
    {
        $attachment = Attachment::where("id", $attachment_id)->firstOrFail();
        return AttachmentResource::make($attachment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AttachmentRequest $request, $attachment_id)
    {
        $attachment = Attachment::findOrFail($attachment_id);
        $data = $request->validated();

        // Check if a new file is uploaded
        if ($request->hasFile('file_path')) {
            // Delete the old file if it exists
            if ($attachment->file_path) {
                Storage::disk('public')->delete($attachment->file_path);
            }

            // Store the new file in the 'attachments' directory
            $data['file_path'] = $request->file('file_path')->store('attachments', 'public');
        }

        // Update the attachment
        $attachment->update($data);
        // Dispatch event
        broadcast(new AttachmentUpdated($attachment));

        return AttachmentResource::make($attachment->refresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($attachment_id)
    {
        $attachment = Attachment::where("id", $attachment_id)->firstOrFail();
        if ($attachment->file_path) {
            Storage::disk('public')->delete($attachment->file_path);
        }
        // Dispatch event
        broadcast(new AttachmentUpdated($attachment));
        return $attachment->delete();
    }
}
