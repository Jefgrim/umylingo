<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    /** @use HasFactory<\Database\Factories\AchievementFactory> */
    use HasFactory;
    protected $fillable = [
        'deck_id',
        'achievement_title',
        'achievement_description',
    ];

    public function userAchievements()
    {
        return $this->hasMany(UserAchievement::class);
    }
    public function deck()
    {
        return $this->belongsTo(Deck::class);
    }
}
