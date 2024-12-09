<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizProgress extends Model
{
    protected $casts = [
        'startedAt' => 'datetime',
        'completedAt' => 'datetime',
    ];
    protected $fillable = [
        'deck_id',
        'user_id',
        'learn_progress_id',
        'correctItems',
        'totalItems',
        'currentIndex',
        'isStarted',
        'isCompleted',
        'startedAt',
        'completedAt',
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
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
}
