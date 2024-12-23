<?php

namespace App\Policies;

use App\Models\BoardList;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ListPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the list.
     */
    public function view(User $user, BoardList $list): bool
    {
        return $user->memberBoards()->where('board_id', $list->board_id)->exists();
    }

    /**
     * Determine whether the user can create lists.
     */
    public function create(User $user, BoardList $list): bool
    {
        return $user->memberBoards()
            ->where('board_id', $list->board_id)
            ->wherePivot('role', 'admin')
            ->exists();
    }

    /**
     * Determine whether the user can update the list.
     */
    public function update(User $user, BoardList $list): bool
    {
        return $user->memberBoards()
            ->where('board_id', $list->board_id)
            ->wherePivot('role', 'admin')
            ->exists();
    }

    /**
     * Determine whether the user can delete the list.
     */
    public function delete(User $user, BoardList $list): bool
    {
        return $user->memberBoards()
            ->where('board_id', $list->board_id)
            ->wherePivot('role', 'admin')
            ->exists();
    }
}
