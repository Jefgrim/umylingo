<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    public function created(User $user): void
    {
        Log::info('User created', [
            'user_id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
        ]);
    }

    public function updated(User $user): void
    {
        $changes = $user->getChanges();
        Log::info('User updated', [
            'user_id' => $user->id,
            'username' => $user->username,
            'changes' => $changes,
        ]);
    }

    public function deleted(User $user): void
    {
        Log::warning('User deleted', [
            'user_id' => $user->id,
            'username' => $user->username,
        ]);
    }
}
