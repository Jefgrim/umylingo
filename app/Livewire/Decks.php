<?php

namespace App\Livewire;

use App\Models\Deck;
use Livewire\Component;

class Decks extends Component
{
    public function render()
    {
        return view('livewire.decks',[
            'decks' => Deck::all()
        ]);
    }
}
