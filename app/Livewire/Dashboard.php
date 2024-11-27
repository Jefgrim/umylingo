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
    public $currentUsersPerMonth;
    public $totalDecks;
    public $totalCards;
    public $totalQuizzesStarted;
    public $totalQuizzesCompleted;

    public function mount()
    {
        // Create an array of months (1 to 12)
        $months = range(1, 12);
        $this->currentUsers = User::where('isAdmin', false)->count();
        
        $userCounts = User::where('isAdmin', false)
            ->whereYear('created_at', now()->year)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Initialize the currentUsers array with 0 for each month
        $this->currentUsersPerMonth = array_fill_keys($months, 0);

        // Update the currentUsers array with the actual user counts
        foreach ($userCounts as $month => $count) {
            $this->currentUsersPerMonth[$month] = $count;
        }

        // dd($this->currentUsersPerMonth);
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
