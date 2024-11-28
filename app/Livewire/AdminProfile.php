<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdminProfile extends AdminComponent
{
    public $firstname;
    public $lastname;
    public $username;
    public $email;

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
        // Validate the inputs
        $validatedData = $this->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore(Auth::id()),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore(Auth::id()),
            ],
        ]);

        // Update the user's profile
        $user = Auth::user();
        $user->update($validatedData);

        // Optionally, you can add a success message
        session()->flash('success', 'Profile updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin-profile');
    }
}
