<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\SessionGuard;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    //

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
            'password' => ['required', Password::min(5)],
        ]);

        if (!Auth::attempt($attributes)) {
            throw ValidationException::withMessages([
                'username' => 'Credentials do not match.'
            ]);
        }

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
}
