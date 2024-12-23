<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Checklist;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChecklistController extends Controller
{
    public function store(Request $request, Card $card)
    {
        $this->authorize('update', $card->list->board);

        $request->validate([
            'title' => 'required|string|max:255'
        ]);

        $position = $card->checklists()->max('position') + 1;
        
        $checklist = $card->checklists()->create([
            'title' => $request->title,
            'position' => $position
        ]);

        return response()->json($checklist, 201);
    }

    public function update(Request $request, Checklist $checklist)
    {
        $this->authorize('update', $checklist->card->list->board);

        $request->validate([
            'title' => 'required|string|max:255'
        ]);

        $checklist->update($request->all());

        return response()->json($checklist);
    }

    public function destroy(Checklist $checklist)
    {
        $this->authorize('update', $checklist->card->list->board);

        $checklist->delete();

        return response()->json(null, 204);
    }
}
