<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttachmentRequest;
use App\Http\Resources\AttachmentResource;
use App\Models\Attachment;
use Illuminate\Http\Request;

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
        return AttachmentResource::make(
            Attachment::create(
                array_merge(
                    ["message_id" => $message_id],
                    $request->validated()
                )
            )
        );
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
        $attachment = Attachment::where("id", $attachment_id)->firstOrFail();
        $attachment->update($request->validated());
        return AttachmentResource::make($attachment->refresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($attachment_id)
    {
        $attachment = Attachment::where("id", $attachment_id)->firstOrFail();
        return $attachment->delete();
    }
}
