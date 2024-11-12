<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;
    protected $fillable = [
        'content',
        'question',
        'answer',
        'deck_id'
    ];

    public function deck()
    {
        return $this->belongsTo(Deck::class);
    }
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
    public function choices()
    {
        return $this->hasMany(Choice::class);
    }
}
