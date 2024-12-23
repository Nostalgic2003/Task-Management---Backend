<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function store(Request $request, Card $card)
    {
        $this->authorize('update', $card->list->board);

        $request->validate([
            'file' => 'required|file|max:10240' // 10MB max
        ]);

        $path = $request->file('file')->store('attachments');

        $attachment = $card->attachments()->create([
            'name' => $request->file('file')->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $request->file('file')->getMimeType()
        ]);

        return response()->json($attachment, 201);
    }

    public function destroy(Attachment $attachment)
    {
        $this->authorize('update', $attachment->card->list->board);

        Storage::delete($attachment->path);
        $attachment->delete();

        return response()->json(null, 204);
    }
}
