<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Board\StoreBoardRequest;
use App\Http\Requests\Board\UpdateBoardRequest;
use App\Models\Board;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BoardController extends Controller
{
    use AuthorizesRequests;

    
    public function index(): JsonResponse
    {
        $boards = auth()->user()->memberBoards()
            ->with(['lists.cards', 'members'])
            ->get();

        return response()->json($boards);
    }

public function store(StoreBoardRequest $request): JsonResponse
{
    // Remove authorization check since we're already requiring authentication
    $board = auth()->user()->ownedBoards()->create($request->validated());
    
    // Add creator as admin member
    $board->members()->attach(auth()->id(), ['role' => 'admin']);

    return response()->json($board, 201);
}

    public function show(Board $board): JsonResponse
    {
        $this->authorize('view', $board);

        $board->load([
            'lists.cards.checklists.items',  // Load checklists and their items
            'lists.cards.labels',            // Load labels
            'lists.cards.members',           // Load card members
            'lists.cards.attachments',       // Load attachments
            'lists.cards.comments.user',     // Load comments with their users
            'members'                        // Board members
        ]);
        
        return response()->json($board);
    }

    public function update(UpdateBoardRequest $request, Board $board): JsonResponse
    {
        $this->authorize('update', $board);

        $board->update($request->validated());

        return response()->json($board);
    }

    public function destroy(Board $board): JsonResponse
    {
        $this->authorize('delete', $board);

        $board->delete();

        return response()->json(null, 204);
    }

    public function addMember(Request $request, Board $board): JsonResponse
    {
        $this->authorize('update', $board);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:admin,member'
        ]);

        $user = User::findOrFail($request->user_id);
        $board->members()->attach($user->id, ['role' => $request->role]);

        return response()->json($board->load('members'));
    }

    public function removeMember(Board $board, User $user): JsonResponse
    {
        $this->authorize('update', $board);

        if ($board->user_id === $user->id) {
            return response()->json(['message' => 'Cannot remove board owner'], 403);
        }

        $board->members()->detach($user->id);

        return response()->json($board->load('members'));
    }
}