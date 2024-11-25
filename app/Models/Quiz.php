<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;
    protected $fillable = [
        'card_id',
        'user_id',
        'isAnswered',
    ];

    public function choice()
    {
        return $this->belongsTo(Choice::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
