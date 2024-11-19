<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


class RegisteredUserController extends Controller
{
    //

    public function create()
    {
        if (Auth::guest()) {
            return view('auth.register');
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
            'username' => ['required', 'unique:users'],
            'password' => ['required', Password::min(5), 'confirmed'],
            'email' => ['required', 'email', 'unique:users'],
            'firstname' => ['required'],
            'lastname' => ['required'],
        ]);

        //create the user

        $user = User::create($attributes);

        //log in
        Auth::login($user);

        //redirect
        if (Auth::user()->isAdmin) {
            return redirect('/dashboard');
        } else {
            return redirect('/decks');
        }
    }
}
