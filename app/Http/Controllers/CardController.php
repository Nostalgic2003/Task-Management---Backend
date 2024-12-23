<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Card\StoreCardRequest;
use App\Http\Requests\Card\UpdateCardRequest;
use App\Models\BoardList;
use App\Models\Card;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function store(StoreCardRequest $request, BoardList $list): JsonResponse
    {
        $this->authorize('update', $list->board);

        $position = $list->cards()->max('position') + 1;
        
        $card = $list->cards()->create([
            ...$request->validated(),
            'position' => $position
        ]);

        return response()->json($card, 201);
    }

    public function show(BoardList $list, Card $card): JsonResponse
    {
        $this->authorize('view', $list->board);

        $card->load([
            'members',
            'labels',
            'checklists.items',
            'attachments',
            'comments.user'
        ]);

        return response()->json($card);
    }

    public function update(UpdateCardRequest $request, BoardList $list, Card $card): JsonResponse
    {
        $this->authorize('update', $list->board);

        $card->update($request->validated());

        return response()->json($card);
    }

    public function destroy(BoardList $list, Card $card): JsonResponse
    {
        $this->authorize('update', $list->board);

        $card->delete();

        return response()->json(null, 204);
    }

    public function reorder(Request $request, BoardList $list): JsonResponse
    {
        $this->authorize('update', $list->board);

        $request->validate([
            'cards' => 'required|array',
            'cards.*.id' => 'required|exists:cards,id',
            'cards.*.position' => 'required|integer|min:0',
            'cards.*.board_list_id' => 'required|exists:board_lists,id'
        ]);

        foreach ($request->cards as $cardData) {
            Card::where('id', $cardData['id'])->update([
                'position' => $cardData['position'],
                'board_list_id' => $cardData['board_list_id']
            ]);
        }

        return response()->json($list->cards()->orderBy('position')->get());
    }

    public function addMember(Card $card, Request $request): JsonResponse
    {
        $this->authorize('update', $card->list->board);

        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::findOrFail($request->user_id);
        $card->members()->attach($user->id);

        return response()->json($card->load('members'));
    }

    public function removeMember(Card $card, User $user): JsonResponse
    {
        $this->authorize('update', $card->list->board);

        $card->members()->detach($user->id);

        return response()->json($card->load('members'));
    }
}