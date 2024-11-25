<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizProgress extends Model
{
    protected $fillable = [
        'deck_id',
        'user_id',
        'learn_progress_id',
        'score',
        'currentIndex',
        'isStarted',
        'isCompleted',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function deck()
    {
        return $this->belongsTo(Deck::class);
    }
    public function learnProgress()
    {
        return $this->belongsTo(LearnProgress::class);
    }
}
