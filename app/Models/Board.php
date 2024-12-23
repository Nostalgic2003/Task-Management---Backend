<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Board extends Model
{
    protected $fillable = [
        'name',
        'description',
        'user_id',
        'visibility'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lists(): HasMany
    {
        return $this->hasMany(BoardList::class)->orderBy('position');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'board_users')
                    ->withPivot('role')
                    ->withTimestamps();
    }

      public function labels()
    {
        return $this->hasMany(Label::class);
    }
}