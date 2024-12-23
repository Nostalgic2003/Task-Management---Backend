<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Card;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    public function store(Request $request, Card $card)
    {
        $this->authorize('update', $card->list->board);

        $request->validate([
            'content' => 'required|string'
        ]);

        $comment = $card->comments()->create([
            'content' => $request->content,
            'user_id' => auth()->id()
        ]);

        return response()->json($comment->load('user'), 201);
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment->card->list->board);

        $request->validate([
            'content' => 'required|string'
        ]);

        $comment->update($request->all());

        return response()->json($comment->load('user'));
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('update', $comment->card->list->board);

        $comment->delete();

        return response()->json(null, 204);
    }
}
