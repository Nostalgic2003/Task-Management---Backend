<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{ 
    use HasFactory;

    protected $fillable = ['name', 'color', 'board_id'];

    public function cards()
    {
        return $this->belongsToMany(Card::class);
    }

    public function board()
    {
        return $this->belongsTo(Board::class);
    }
}
