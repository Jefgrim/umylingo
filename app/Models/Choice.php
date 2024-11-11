<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    /** @use HasFactory<\Database\Factories\ChoiceFactory> */
    use HasFactory;
    protected $fillable = [
        'card_id',
        'choice',
        'isCorrect'
    ];

    public function card(){
        return $this->belongsTo(Card::class);
    }

}
