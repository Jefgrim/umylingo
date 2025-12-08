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
            'password' => ['required', Password::min(10)],
        ]);

        // Check if username exists
        $user = User::where('username', $attributes['username'])->first();
        
        if (!$user) {
            throw ValidationException::withMessages([
                'username' => 'Username not found.'
            ]);
        }

        if (!Auth::attempt($attributes)) {
            throw ValidationException::withMessages([
                'password' => 'Incorrect password.'
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
