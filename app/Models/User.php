<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'locked_until' => 'datetime',
        ];
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
    public function userAchievements()
    {
        return $this->hasMany(UserAchievement::class);
    }
    public function learnProgress()
    {
        return $this->hasMany(LearnProgress::class);
    }

    public function progress()
    {
        return $this->hasOne(UserProgress::class);
    }

    public function isAccountLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    public function getRemainingLockTime(): int
    {
        if (!$this->isAccountLocked()) {
            return 0;
        }
        return $this->locked_until->diffInMinutes(now());
    }

    public function incrementLoginAttempts(): void
    {
        $this->increment('login_attempts');
    }

    public function resetLoginAttempts(): void
    {
        $this->update([
            'login_attempts' => 0,
            'locked_until' => null,
        ]);
    }

    public function lockAccount(): void
    {
        $this->update([
            'locked_until' => now()->addMinutes(15),
        ]);
    }
}
