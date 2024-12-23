<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Checklist;
use App\Models\ChecklistItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChecklistItemController extends Controller
{
    public function store(Request $request, Card $card, Checklist $checklist)
    {
        $this->authorize('update', $card->list->board);

        if ($checklist->card_id !== $card->id) {
            return response()->json(['message' => 'Checklist does not belong to this card'], 403);
        }

        $request->validate([
            'content' => 'required_without:title|string|max:255',
            'title' => 'required_without:content|string|max:255'
        ]);

        $position = $checklist->items()->max('position') + 1;

        $item = $checklist->items()->create([
            'title' => $request->content ?? $request->title,
            'position' => $position
        ]);

        return response()->json($item, 201);
    }

    public function update(Request $request, Card $card, Checklist $checklist, ChecklistItem $item)
    {
        $this->authorize('update', $card->list->board);

        if ($checklist->card_id !== $card->id) {
            return response()->json(['message' => 'Checklist does not belong to this card'], 403);
        }

        if ($item->checklist_id !== $checklist->id) {
            return response()->json(['message' => 'Item does not belong to this checklist'], 403);
        }

        $request->validate([
            'is_completed' => 'required|boolean'
        ]);

        $item->update([
            'is_completed' => $request->is_completed
        ]);

        return response()->json($item);
    }

    public function destroy(Card $card, Checklist $checklist, ChecklistItem $item)
    {
        $this->authorize('update', $card->list->board);

        if ($checklist->card_id !== $card->id) {
            return response()->json(['message' => 'Checklist does not belong to this card'], 403);
        }

        if ($item->checklist_id !== $checklist->id) {
            return response()->json(['message' => 'Item does not belong to this checklist'], 403);
        }

        $item->delete();

        return response()->json(null, 204);
    }
}
