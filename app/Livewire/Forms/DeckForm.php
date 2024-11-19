<?php

namespace App\Livewire\Forms;

use App\Models\Achievement;
use App\Models\Deck;
use Livewire\Attributes\Validate;
use Livewire\Form;

class DeckForm extends Form
{
    #[Validate('required')]
    public $language;

    #[Validate('required')]
    public $deck_description;

    #[Validate('required')]
    public $achievement_title;

    #[Validate('required')]
    public $achievement_description;

    public function create()
    {
        $this->validate();

        $deck = Deck::create($this->only(['language', 'deck_description']));
        $deck_id = $deck->id;
        Achievement::create([
            'deck_id' => $deck_id,
            'achievement_title' => $this->achievement_title,
            'achievement_description' => $this->achievement_description,
        ]);
    }
}
