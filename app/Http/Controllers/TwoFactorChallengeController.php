<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;

class TwoFactorChallengeController extends Controller
{
    private const MAX_CHALLENGE_ATTEMPTS = 5;
    private const LOCK_TIME_SECONDS = 300;

    public function create(Request $request)
    {
        if (!$request->session()->has('login.id')) {
            return redirect('/login');
        }

        return view('auth.two-factor-challenge');
    }

    public function store(Request $request, TwoFactorAuthenticationProvider $provider)
    {
        $request->validate([
            'code' => ['nullable', 'string'],
            'recovery_code' => ['nullable', 'string'],
        ]);

        $loginId = $request->session()->get('login.id');
        $remember = (bool) $request->session()->get('login.remember');

        if (!$loginId) {
            return redirect('/login');
        }

        $user = User::find($loginId);

        if (!$user || !$user->two_factor_secret || !$user->two_factor_confirmed_at) {
            $request->session()->forget(['login.id', 'login.remember']);
            return redirect('/login');
        }

        $challengeKey = $this->throttleKey($request->ip());

        if (RateLimiter::tooManyAttempts($challengeKey, self::MAX_CHALLENGE_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($challengeKey);
            $minutesRemaining = (int) max(1, ceil($seconds / 60));

            throw ValidationException::withMessages([
                'code' => "Too many attempts. Please try again in {$minutesRemaining} minute(s).",
            ]);
        }

        $code = trim(str_replace(' ', '', (string) $request->input('code')));
        $recoveryCode = trim((string) $request->input('recovery_code'));

        if ($code === '' && $recoveryCode === '') {
            throw ValidationException::withMessages([
                'code' => 'Enter an authentication code or a recovery code.',
            ]);
        }

        $secret = decrypt($user->two_factor_secret);
        $passed = false;

        if ($code !== '' && $provider->verify($secret, $code)) {
            $passed = true;
        } elseif ($recoveryCode !== '') {
            $recoveryCodes = $this->recoveryCodes($user);
            $matchedIndex = array_search($recoveryCode, $recoveryCodes, true);

            if ($matchedIndex !== false) {
                $passed = true;
                unset($recoveryCodes[$matchedIndex]);
                $user->forceFill([
                    'two_factor_recovery_codes' => encrypt(json_encode(array_values($recoveryCodes))),
                ])->save();
            }
        }

        if (!$passed) {
            RateLimiter::hit($challengeKey, self::LOCK_TIME_SECONDS);

            Log::warning('Two-factor challenge failed', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
            ]);

            throw ValidationException::withMessages([
                'code' => 'The provided authentication code is invalid.',
            ]);
        }

        RateLimiter::clear($challengeKey);
        $request->session()->forget(['login.id', 'login.remember']);

        Auth::login($user, $remember);
        $request->session()->regenerate();

        Log::info('Two-factor challenge passed', [
            'user_id' => $user->id,
            'ip' => $request->ip(),
        ]);

        if ($user->isAdmin) {
            return redirect('/dashboard');
        }

        return redirect('/decks');
    }

    private function recoveryCodes(User $user): array
    {
        if (!$user->two_factor_recovery_codes) {
            return [];
        }

        return json_decode(decrypt($user->two_factor_recovery_codes), true) ?: [];
    }

    private function throttleKey(string $ip): string
    {
        return 'two-factor:' . $ip;
    }
}
