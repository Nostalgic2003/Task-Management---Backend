<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\BoardList\StoreBoardListRequest;
use App\Http\Requests\BoardList\UpdateBoardListRequest;
use App\Models\Board;
use App\Models\BoardList;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BoardListController extends Controller
{
    public function index(Board $board): JsonResponse
    {
        $this->authorize('view', $board);

        $lists = $board->lists()->with('cards')->get();

        return response()->json($lists);
    }

    public function store(StoreBoardListRequest $request, Board $board): JsonResponse
    {
        $this->authorize('update', $board);

        $position = $board->lists()->max('position') + 1;
        
        $list = $board->lists()->create([
            ...$request->validated(),
            'position' => $position
        ]);

        return response()->json($list, 201);
    }

    public function update(UpdateBoardListRequest $request, Board $board, BoardList $list): JsonResponse
    {
        $this->authorize('update', $board);

        $list->update($request->validated());

        return response()->json($list);
    }

    public function destroy(Board $board, BoardList $list): JsonResponse
    {
        $this->authorize('update', $board);

        $list->delete();

        return response()->json(null, 204);
    }

    public function reorder(Request $request, Board $board): JsonResponse
    {
        $this->authorize('update', $board);

        $request->validate([
            'lists' => 'required|array',
            'lists.*.id' => 'required|exists:board_lists,id',
            'lists.*.position' => 'required|integer|min:0'
        ]);

        foreach ($request->lists as $listData) {
            $board->lists()->where('id', $listData['id'])
                ->update(['position' => $listData['position']]);
        }

        return response()->json($board->lists()->orderBy('position')->get());
    }
}