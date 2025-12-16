<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;

class Profile extends Component
{
    public $firstname;
    public $lastname;
    public $username;
    public $email;
    public $current_password = '';
    public $password = '';
    public $password_confirmation = '';
    public $two_factor_code = '';
    public $recovery_code = '';

    public function mount()
    {
        // Pre-fill the form with the authenticated user's details
        $user = Auth::user();
        $this->firstname = $user->firstname;
        $this->lastname = $user->lastname;
        $this->username = $user->username;
        $this->email = $user->email;
    }

    public function updateProfile()
    {
        $user = Auth::user();
        if (!($user instanceof \App\Models\User)) {
            $user = \App\Models\User::find(Auth::id());
        }
        $rules = [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'regex:/^[A-Za-z0-9]+$/',
                'max:255',
                Rule::unique('users', 'username')->ignore(Auth::id()),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore(Auth::id()),
            ],
            'current_password' => ['required', 'string'],
        ];

        if ($user->two_factor_secret && $user->two_factor_confirmed_at) {
            $rules['two_factor_code'] = ['required_without:recovery_code', 'nullable', 'string'];
            $rules['recovery_code'] = ['required_without:two_factor_code', 'nullable', 'string'];
        }

        $validatedData = $this->validate($rules, [
            'username.regex' => 'Username may only contain letters and numbers.',
            'current_password.required' => 'Please enter your current password.',
            'two_factor_code.required_without' => 'Enter an authenticator code or a recovery code.',
            'recovery_code.required_without' => 'Enter an authenticator code or a recovery code.',
        ]);

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Current password is incorrect.');
            return;
        }

        if ($user->two_factor_secret && $user->two_factor_confirmed_at) {
            $passed = false;
            $code = trim(str_replace(' ', '', (string) $this->two_factor_code));
            $recoveryCode = trim((string) $this->recovery_code);

            if ($code !== '') {
                $secret = decrypt($user->two_factor_secret);
                if (app(TwoFactorAuthenticationProvider::class)->verify($secret, $code)) {
                    $passed = true;
                }
            }

            if (!$passed && $recoveryCode !== '') {
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
                $this->addError('two_factor_code', 'The provided two-factor code or recovery code is invalid.');
                return;
            }
        }

        // Remove sensitive fields before update
        unset($validatedData['current_password'], $validatedData['two_factor_code'], $validatedData['recovery_code']);

        $user->fill($validatedData);
        $user->save();
        session()->flash('success', 'Profile updated successfully.');
    }

    public function updatePassword(TwoFactorAuthenticationProvider $provider)
    {
        $user = Auth::user();

        $rules = [
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', Password::min(12)->letters()->symbols(), 'confirmed'],
        ];

        if ($user->two_factor_secret && $user->two_factor_confirmed_at) {
            $rules['two_factor_code'] = ['required_without:recovery_code', 'nullable', 'string'];
            $rules['recovery_code'] = ['required_without:two_factor_code', 'nullable', 'string'];
        }

        $this->validate($rules, [
            'current_password.required' => 'Please enter your current password.',
            'password.confirmed' => 'The password confirmation does not match.',
            'two_factor_code.required_without' => 'Enter an authenticator code or a recovery code.',
            'recovery_code.required_without' => 'Enter an authenticator code or a recovery code.',
        ]);

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Current password is incorrect.');
            return;
        }

        if ($user->two_factor_secret && $user->two_factor_confirmed_at) {
            $passed = false;
            $code = trim(str_replace(' ', '', (string) $this->two_factor_code));
            $recoveryCode = trim((string) $this->recovery_code);

            if ($code !== '') {
                $secret = decrypt($user->two_factor_secret);
                if ($provider->verify($secret, $code)) {
                    $passed = true;
                }
            }

            if (!$passed && $recoveryCode !== '') {
                $recoveryCodes = $this->recoveryCodes($user);
                $matchedIndex = array_search($recoveryCode, $recoveryCodes, true);

                if ($matchedIndex !== false) {
                    $passed = true;
                    unset($recoveryCodes[$matchedIndex]);
                    if (!($user instanceof \App\Models\User)) {
                        $user = \App\Models\User::find(Auth::id());
                    }
                    $user->two_factor_recovery_codes = encrypt(json_encode(array_values($recoveryCodes)));
                    $user->save();
                }
            }

            if (!$passed) {
                $this->addError('two_factor_code', 'Invalid authenticator or recovery code.');
                return;
            }
        }


        if (!($user instanceof \App\Models\User)) {
            $user = \App\Models\User::find(Auth::id());
        }
        $user->password = Hash::make($this->password);
        $user->save();

        $this->reset(['current_password', 'password', 'password_confirmation', 'two_factor_code', 'recovery_code']);

        session()->flash('success', 'Password updated successfully.');
    }

    private function recoveryCodes($user): array
    {
        if (!$user->two_factor_recovery_codes) {
            return [];
        }

        return json_decode(decrypt($user->two_factor_recovery_codes), true) ?: [];
    }
    public function render()
    {
        return view('livewire.profile');
    }
}
