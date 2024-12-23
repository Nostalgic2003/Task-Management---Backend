<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BoardUser extends Pivot
{
    protected $table = 'board_users';

    protected $fillable = [
        'board_id',
        'user_id',
        'role'
    ];

    // If you want to use timestamps (created_at, updated_at)
    public $timestamps = true;

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}