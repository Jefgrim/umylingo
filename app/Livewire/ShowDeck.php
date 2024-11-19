<?php

namespace App\Livewire;

use App\Models\Deck;
use Livewire\Component;

class ShowDeck extends Component
{
    public Deck $deck;

    public function mount(Deck $deck)
    {
        $this->deck = $deck;
    }
    public function render()
    {
        return view('livewire.show-deck');
    }
}
