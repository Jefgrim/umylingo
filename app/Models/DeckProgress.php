<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeckProgress extends Model
{
    /** @use HasFactory<\Database\Factories\DeckProgressFactory> */
    use HasFactory;
    protected $fillable = [
        'deck_id',
        'user_id',
        'score',
        'cardLearnIndex',
        'quizLearnIndex',
        'isLearningStarted',
        'isQuizStarted',
        'isLearningCompleted',
        'isQuizStarted',
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function deck(){
        return $this->belongsTo(Deck::class);
    }
}
