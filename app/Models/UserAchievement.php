<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAchievement extends Model
{
    /** @use HasFactory<\Database\Factories\UserAchievementFactory> */
    use HasFactory;
    protected $fillable = [
        'achievement_id',
        'user_id',
        'achieved_at'
    ];

    public function achievement()
    {
        return $this->belongsTo(Achievement::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
