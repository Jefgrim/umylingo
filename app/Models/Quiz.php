<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;
    protected $fillable = [
        'quiz_progress_id',
        'card_id',
        'user_id',
        'choice_id',
        'isAnswered',
        'isCorrect',
    ];

    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function QuizProgress()
    {
        return $this->belongsTo(QuizProgress::class);
    }
    
    public function choice()
    {
        return $this->belongsTo(Choice::class);
    }

}
