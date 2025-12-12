<?php

namespace App\Http\Controllers;

use App\Models\SecurityQuestion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function showResetForm(Request $request)
    {
        $username = $request->query('username');
        
        if (!$username) {
            return redirect()->route('password.request')->with('error', 'Username is required.');
        }

        $user = User::where('username', $username)->first();
        
        if (!$user) {
            return redirect()->route('password.request')->with('error', 'User not found.');
        }

        $has2FA = (bool) $user->two_factor_confirmed_at;
        $hasSecurityQuestions = $user->securityQuestions()->count() > 0;

        if (!$has2FA && !$hasSecurityQuestions) {
            return redirect()->route('password.request')->with('error', 'Password reset not available. Please contact administrator.');
        }

        // Get security questions if user has them
        $questions = $hasSecurityQuestions ? $user->securityQuestions : collect();

        return view('auth.reset-password', [
            'username' => $username,
            'has2FA' => $has2FA,
            'hasSecurityQuestions' => $hasSecurityQuestions,
            'questions' => $questions,
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::min(12)->letters()->symbols()],
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'username' => 'User not found.',
            ]);
        }

        $has2FA = (bool) $user->two_factor_confirmed_at;
        $hasSecurityQuestions = $user->securityQuestions()->count() > 0;

        // Validate based on what user has
        $verified = false;

        if ($has2FA && $request->filled('recovery_code')) {
            // Verify recovery code
            $verified = $this->verifyRecoveryCode($user, $request->recovery_code);
            
            if (!$verified) {
                throw ValidationException::withMessages([
                    'recovery_code' => 'Invalid recovery code.',
                ]);
            }
        } elseif ($hasSecurityQuestions) {
            // Verify security questions
            $verified = $this->verifySecurityQuestions($user, $request);
            
            if (!$verified) {
                throw ValidationException::withMessages([
                    'answers' => 'One or more security answers are incorrect.',
                ]);
            }
        } else {
            throw ValidationException::withMessages([
                'username' => 'Password reset not available.',
            ]);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        Log::info('Password reset via security verification', [
            'user_id' => $user->id,
            'method' => $has2FA && $request->filled('recovery_code') ? '2fa_recovery' : 'security_questions',
        ]);

        return redirect()->route('login')->with('status', 'Password has been reset successfully. Please login with your new password.');
    }

    private function verifyRecoveryCode(User $user, string $code): bool
    {
        if (!$user->two_factor_recovery_codes) {
            return false;
        }

        $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);
        
        if (!is_array($recoveryCodes)) {
            return false;
        }

        $code = trim(str_replace(' ', '', $code));
        
        if (!in_array($code, $recoveryCodes)) {
            return false;
        }

        // Remove used recovery code
        $recoveryCodes = array_values(array_filter($recoveryCodes, fn($c) => $c !== $code));
        
        $user->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
        ])->save();

        return true;
    }

    private function verifySecurityQuestions(User $user, Request $request): bool
    {
        $questions = $user->securityQuestions;
        
        if ($questions->count() === 0) {
            return false;
        }

        foreach ($questions as $index => $question) {
            $answer = $request->input("answer_{$question->id}");
            
            if (!$answer || !Hash::check(strtolower(trim($answer)), $question->answer)) {
                return false;
            }
        }

        return true;
    }
}
