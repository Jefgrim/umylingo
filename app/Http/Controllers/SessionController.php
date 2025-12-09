<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\LoginAttemptsLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\SessionGuard;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCK_TIME_MINUTES = 15;

    public function create()
    {
        if (Auth::guest()) {
            return view('auth.login');
        } elseif (Auth::user()->isAdmin) {
            return redirect('/dashboard');
        } else {
            return redirect('/decks');
        }
    }

    public function store()
    {
        //validate
        $attributes = request()->validate([
            'username' => ['required'],
            'password' => ['required', Password::min(10)],
        ]);

        $ipAddress = request()->ip();

        // Check if too many failed attempts from this IP address
        $failedAttemptsFromIP = LoginAttemptsLog::where('ip_address', $ipAddress)
            ->where('success', false)
            ->where('created_at', '>', now()->subMinutes(self::LOCK_TIME_MINUTES))
            ->count();

        if ($failedAttemptsFromIP >= self::MAX_LOGIN_ATTEMPTS) {
            $oldestAttempt = LoginAttemptsLog::where('ip_address', $ipAddress)
                ->where('success', false)
                ->where('created_at', '>', now()->subMinutes(self::LOCK_TIME_MINUTES))
                ->orderBy('created_at', 'asc')
                ->first();

            if ($oldestAttempt) {
                $lockUntil = $oldestAttempt->created_at->addMinutes(self::LOCK_TIME_MINUTES);
                $minutesRemaining = (int) now()->diffInMinutes($lockUntil);
            } else {
                $minutesRemaining = self::LOCK_TIME_MINUTES;
            }

            throw ValidationException::withMessages([
                'username' => "Too many login attempts from your IP. Please try again in {$minutesRemaining} minute(s)."
            ]);
        }

        // Check if username exists
        $user = User::where('username', $attributes['username'])->first();
        
        if (!$user) {
            $this->logLoginAttempt($attributes['username'], false, 'Username not found');
            $failedAttemptsFromIP++;
            $remainingAttempts = self::MAX_LOGIN_ATTEMPTS - $failedAttemptsFromIP;
            throw ValidationException::withMessages([
                'username' => "Username not found. You have {$remainingAttempts} attempt(s) remaining."
            ]);
        }

        // Check if account is locked
        if ($user->isAccountLocked()) {
            $remainingTime = $user->getRemainingLockTime();
            $this->logLoginAttempt($attributes['username'], false, 'Account locked');
            throw ValidationException::withMessages([
                'username' => "Account is locked. Try again in {$remainingTime} minute(s)."
            ]);
        }

        // Attempt login
        if (!Auth::attempt($attributes)) {
            $this->logLoginAttempt($attributes['username'], false, 'Incorrect password');

            // Check if max attempts reached from this IP
            $failedAttemptsFromIP++;
            if ($failedAttemptsFromIP >= self::MAX_LOGIN_ATTEMPTS) {
                $oldestAttempt = LoginAttemptsLog::where('ip_address', $ipAddress)
                    ->where('success', false)
                    ->where('created_at', '>', now()->subMinutes(self::LOCK_TIME_MINUTES))
                    ->orderBy('created_at', 'asc')
                    ->first();

                if ($oldestAttempt) {
                    $lockUntil = $oldestAttempt->created_at->addMinutes(self::LOCK_TIME_MINUTES);
                    $minutesRemaining = (int) now()->diffInMinutes($lockUntil);
                } else {
                    $minutesRemaining = self::LOCK_TIME_MINUTES;
                }

                throw ValidationException::withMessages([
                    'password' => "Too many failed login attempts. Please try again in {$minutesRemaining} minute(s)."
                ]);
            }

            $remainingAttempts = self::MAX_LOGIN_ATTEMPTS - $failedAttemptsFromIP;
            throw ValidationException::withMessages([
                'password' => "Incorrect password. You have {$remainingAttempts} attempt(s) remaining."
            ]);
        }

        // Successful login
        $this->logLoginAttempt($attributes['username'], true, 'Successful login');
        
        // Reset failed attempts for this IP
        LoginAttemptsLog::where('ip_address', $ipAddress)
            ->where('success', false)
            ->where('created_at', '>', now()->subMinutes(self::LOCK_TIME_MINUTES))
            ->delete();

        request()->session()->regenerate();

        //redirect
        if (Auth::user()->isAdmin) {
            return redirect('/dashboard');
        } else {
            return redirect('/decks');
        }
    }

    public function destroy()
    {
        Auth::logout();
        return redirect('/login');
    }

    private function logLoginAttempt(string $username, bool $success, string $reason): void
    {
        LoginAttemptsLog::create([
            'username' => $username,
            'ip_address' => request()->ip(),
            'success' => $success,
            'reason' => $reason,
        ]);
    }
}
