<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    /** @use HasFactory<\Database\Factories\AchievementFactory> */
    use HasFactory;
    protected $fillable = [
        'achievement_title',
        'achievement_description',
        'achievement_requirement',
    ];

    public function userAchievements()
    {
        return $this->hasMany(UserAchievement::class);
    }
}
