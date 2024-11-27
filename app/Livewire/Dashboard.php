<?php

namespace App\Livewire;

use App\Models\Card;
use App\Models\Deck;
use App\Models\QuizProgress;
use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Admin Dashboard')]
class Dashboard extends AdminComponent
{
    public $currentUsers;
    public $totalDecks;
    public $totalCards;
    public $totalQuizzesStarted;
    public $totalQuizzesCompleted;

    public function mount(){
        $this->currentUsers = User::where('isAdmin', false)->count();
        $this->totalDecks = Deck::all()->count();
        $this->totalCards = Card::all()->count();
        $this->totalQuizzesStarted = QuizProgress::where('isStarted', true)->count();
        $this->totalQuizzesCompleted = QuizProgress::where('isCompleted', true)->count();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
