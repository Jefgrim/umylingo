<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearnProgress extends Model
{
    protected $fillable = [
        'deck_id',
        'user_id',
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
    public function quizProgress()
    {
        return $this->hasOne(QuizProgress::class);
    }

}
