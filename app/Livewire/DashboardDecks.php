<?php

namespace App\Livewire;

use App\Models\Deck;
use Livewire\Attributes\Title;
use Livewire\Component;

class DashboardDecks extends AdminComponent
{
    #[Title('Admin Decks View')]
    public function render()
    {
        return view('livewire.dashboard-decks', [
            'decks' => Deck::all()
        ]);
    }
}
