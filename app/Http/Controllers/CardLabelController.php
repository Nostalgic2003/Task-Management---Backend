<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CardLabelController extends Controller
{
    public function attach(Request $request, Card $card): JsonResponse
    {
        $this->authorize('update', $card->list->board);

        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7'  // For hex color codes
        ]);

        // Create a new label for the board
        $label = $card->list->board->labels()->create([
            'name' => $request->name,
            'color' => $request->color
        ]);

        // Attach the new label to the card
        $card->labels()->attach($label->id);

        return response()->json($card->load('labels'));
    }

    public function detach(Card $card, Label $label): JsonResponse
    {
        $this->authorize('update', $card->list->board);

        // First check if the label exists and belongs to the card
        if (!$card->labels()->where('label_id', $label->id)->exists()) {
            return response()->json(['message' => 'Label is not attached to this card'], 404);
        }

        // Check if label belongs to the same board
        if ($label->board_id !== $card->list->board_id) {
            return response()->json(['message' => 'Label does not belong to this board'], 403);
        }

        $card->labels()->detach($label->id);

        return response()->json($card->load('labels'));
    }
}
