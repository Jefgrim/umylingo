<?php

namespace App\Livewire;

use App\Models\Deck;
use Livewire\Attributes\Title;
use Livewire\Component;

class Decks extends Component
{
    #[Title('Decks View')]
    public function render()
    {
        return view('livewire.decks',[
            'decks' => Deck::all()
        ]);
    }
}
