<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    public function learnProgress()
    {
        return $this->hasMany(LearnProgress::class);
    }
}
