<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    /** @use HasFactory<\Database\Factories\ProgressFactory> */
    use HasFactory;
    protected $fillable = [
        'deck_id',
        'user_id',
        'score',
        'isCompleted',
        'isQuizStarted',
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function deck(){
        return $this->belongsTo(Deck::class);
    }
}
