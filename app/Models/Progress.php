<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    /** @use HasFactory<\Database\Factories\ProgressFactory> */
    use HasFactory;
    protected $fillable = [
        'score',
        'isCompleted',
        'isQuizStarted',
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
