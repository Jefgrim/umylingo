<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Deck extends Model
{
    use HasFactory;
    protected $fillable = [
        'achievement_id',
        'language',
        'deck_description',
        'isArchived'
    ];

    public function cards()
    {
        return $this->hasMany(Card::class);
    }
    public function progress()
    {
        return $this->hasMany(DeckProgress::class);
    }
}
