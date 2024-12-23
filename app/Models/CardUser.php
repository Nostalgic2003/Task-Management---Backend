<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CardUser extends Pivot
{
    protected $table = 'card_users';

    protected $fillable = [
        'card_id',
        'user_id'
    ];

    // If you want to use timestamps (created_at, updated_at)
    public $timestamps = true;

    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}