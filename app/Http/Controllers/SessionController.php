<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCK_TIME_SECONDS = 60; // 15 minutes

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
        // Validate input
        $attributes = request()->validate([
            'username' => ['required', 'regex:/^[A-Za-z0-9]+$/'],
            'password' => ['required', Password::min(12)],
        ], [
            'username.regex' => 'Username may only contain letters and numbers.',
        ]);

        $ipAddress = request()->ip();
        $key = $this->throttleKey($ipAddress);

        // Rate limit guard
        if (RateLimiter::tooManyAttempts($key, self::MAX_LOGIN_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($key);
            $minutesRemaining = (int) max(1, ceil($seconds / 60));
            Log::warning('Login blocked due to rate limit', [
                'username' => $attributes['username'],
                'ip' => $ipAddress,
                'retry_after_seconds' => $seconds,
            ]);
            throw ValidationException::withMessages([
                'username' => "Too many login attempts. Please try again in {$minutesRemaining} minute(s)."
            ]);
        }

        // Check if username exists
        $user = User::where('username', $attributes['username'])->first();
        
        if (!$user) {
            RateLimiter::hit($key, self::LOCK_TIME_SECONDS);
            $remainingAttempts = max(0, self::MAX_LOGIN_ATTEMPTS - RateLimiter::attempts($key));
            Log::warning('Login failed - username not found', [
                'username' => $attributes['username'],
                'ip' => $ipAddress,
                'remaining_attempts' => $remainingAttempts,
            ]);
            throw ValidationException::withMessages([
                'username' => "Username not found. You have {$remainingAttempts} attempt(s) remaining."
            ]);
        }

        // Check password
        if (!Hash::check($attributes['password'], $user->password)) {
            RateLimiter::hit($key, self::LOCK_TIME_SECONDS);
            $remainingAttempts = max(0, self::MAX_LOGIN_ATTEMPTS - RateLimiter::attempts($key));

            if (RateLimiter::tooManyAttempts($key, self::MAX_LOGIN_ATTEMPTS)) {
                $seconds = RateLimiter::availableIn($key);
                $minutesRemaining = (int) max(1, ceil($seconds / 60));
                Log::warning('Login locked out', [
                    'username' => $attributes['username'],
                    'ip' => $ipAddress,
                    'retry_after_seconds' => $seconds,
                ]);
                throw ValidationException::withMessages([
                    'password' => "Too many failed login attempts. Please try again in {$minutesRemaining} minute(s)."
                ]);
            }

            Log::warning('Login failed - incorrect password', [
                'username' => $attributes['username'],
                'ip' => $ipAddress,
                'remaining_attempts' => $remainingAttempts,
            ]);

            throw ValidationException::withMessages([
                'password' => "Incorrect password. You have {$remainingAttempts} attempt(s) remaining."
            ]);
        }

        // Require two-factor challenge when enabled
        if ($user->two_factor_secret && $user->two_factor_confirmed_at) {
            RateLimiter::clear($key);
            Log::info('Login requires two-factor challenge', [
                'username' => $attributes['username'],
                'ip' => $ipAddress,
            ]);

            request()->session()->put('login.id', $user->id);
            request()->session()->put('login.remember', false);

            return redirect()->route('two-factor.challenge');
        }

        // Successful login
        RateLimiter::clear($key);
        Auth::login($user);
        Log::info('Login successful', [
            'username' => $attributes['username'],
            'ip' => $ipAddress,
        ]);

        request()->session()->regenerate();

        //redirect
        if (Auth::user()->isAdmin) {
            return redirect('/dashboard');
        }
        return redirect('/decks');
    }

    private function throttleKey(string $ip): string
    {
        return 'login:' . $ip;
    }

    public function destroy()
    {
        Auth::logout();
        
        // Clear sensitive session data on logout
        request()->session()->forget('logs_2fa_passed_at');
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        
        return redirect('/login');
    }
}
