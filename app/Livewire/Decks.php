<?php

namespace App\Livewire;

use App\Models\Deck;
use App\Models\DeckProgress;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

class Decks extends Component
{
    #[Title('Decks View')]
    public function render()
    {

        $decks = Deck::all();

        foreach ($decks as $deck) {
            DeckProgress::firstOrCreate([
                'deck_id' => $deck->id,
                'user_id' => Auth::id(),
            ]);
        }

        $deckProgresses = DeckProgress::where('user_id', Auth::id())->get();

        // dd($deckProgresses[0]->deck->id);

        return view('livewire.decks',[
            'deckProgresses' => $deckProgresses
        ]);
    }
}
