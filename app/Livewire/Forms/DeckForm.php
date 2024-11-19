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

    public ?Deck $deck;

    public function store()
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

    public function setDeck(Deck $deck)
    {
        $this->deck = $deck;
        $this->language = $deck->language;
        $this->deck_description = $deck->deck_description;
        $this->achievement_title = $deck->achievement->achievement_title;
        $this->achievement_description = $deck->achievement->achievement_description;
    }
    public function update()
    {
        $this->validate();

        $this->deck->update($this->only('language', 'deck_description'));
        $this->deck->achievement->update($this->only('achievement_title', 'achievement_description'));
    }
}
