<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProgress extends Model
{
    protected $fillable = [
        'points',
        'level',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
