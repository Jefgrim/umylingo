<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Laravel\Fortify\RecoveryCode;

class TwoFactorController extends Controller
{
    public function edit(Request $request, TwoFactorAuthenticationProvider $provider)
    {
        /** @var User $user */
        $user = $request->user();

        if (!$user->two_factor_secret) {
            $this->seedTwoFactor($user, $provider);
        }

        $secret = decrypt($user->two_factor_secret);
        $recoveryCodes = $this->recoveryCodes($user);

        return view('auth.two-factor-settings', [
            'qrCodeUrl' => $provider->qrCodeUrl(config('app.name'), $user->email ?? $user->username, $secret),
            'secret' => $secret,
            'recoveryCodes' => $recoveryCodes,
            'hasConfirmedTwoFactor' => (bool) $user->two_factor_confirmed_at,
        ]);
    }

    public function store(Request $request, TwoFactorAuthenticationProvider $provider)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        /** @var User $user */
        $user = $request->user();

        if (!$user->two_factor_secret) {
            throw ValidationException::withMessages([
                'code' => 'Two-factor authentication has not been initialized.',
            ]);
        }

        $secret = decrypt($user->two_factor_secret);

        if (!$provider->verify($secret, trim(str_replace(' ', '', $request->input('code'))))) {
            throw ValidationException::withMessages([
                'code' => 'The provided code is invalid.',
            ]);
        }

        $user->forceFill([
            'two_factor_confirmed_at' => now(),
        ])->save();

        Log::info('Two-factor authentication enabled', [
            'user_id' => $user->id,
        ]);

        return redirect()->route('two-factor.index')->with('status', 'Two-factor authentication enabled.');
    }

    public function regenerate(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        if (!$user->two_factor_secret || !$user->two_factor_confirmed_at) {
            return redirect()->route('two-factor.index')->with('status', 'Enable two-factor authentication first.');
        }

        $user->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode($this->generateCodes())),
        ])->save();

        Log::info('Two-factor recovery codes regenerated', [
            'user_id' => $user->id,
        ]);

        return redirect()->route('two-factor.index')->with('status', 'Recovery codes regenerated.');
    }

    public function destroy(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        Log::info('Two-factor authentication disabled', [
            'user_id' => $user->id,
        ]);

        return redirect()->route('two-factor.index')->with('status', 'Two-factor authentication disabled.');
    }

    private function seedTwoFactor(User $user, TwoFactorAuthenticationProvider $provider): void
    {
        $user->forceFill([
            'two_factor_secret' => encrypt($provider->generateSecretKey()),
            'two_factor_recovery_codes' => encrypt(json_encode($this->generateCodes())),
            'two_factor_confirmed_at' => null,
        ])->save();
    }

    private function generateCodes(): array
    {
        return collect(range(1, 8))
            ->map(fn () => RecoveryCode::generate())
            ->all();
    }

    private function recoveryCodes(User $user): array
    {
        if (!$user->two_factor_recovery_codes) {
            return [];
        }

        return json_decode(decrypt($user->two_factor_recovery_codes), true) ?: [];
    }
}
