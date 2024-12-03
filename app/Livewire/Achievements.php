<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Achievements extends Component
{
    public $achievements;
    public function mount()
    {
        $this->achievements = Auth::user()->userAchievements;

        // dd($this->achievements[0]->achievement);
    }
    public function render()
    {
        return view('livewire.achievements');
    }
}
