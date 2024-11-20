<?php

namespace App\Livewire;

use App\Livewire\Forms\DeckForm;
use Livewire\Attributes\Title;
use Livewire\Component;

class CreateDeck extends AdminComponent
{
    public DeckForm $form;

    public $cardCount = 1;
    public $cards = [];

    public function mount()
    {
        $this->initializeCards();
    }

    public function updatedCardCount()
    {
        $this->initializeCards();
    }

    private function initializeCards()
    {
        // Ensure existing data is preserved and only add new cards if necessary
        $existingCount = count($this->cards);

        $cardCount = (int) $this->cardCount; // Ensure $cardCount is an integer

        if ($existingCount < $cardCount) {
            for ($i = $existingCount; $i < $cardCount; $i++) {
                $this->cards[$i] = [
                    'content' => '',
                    'question' => '',
                    'answer' => '',
                    'choices' => [
                        ['choice' => '', 'isCorrect' => false],
                        ['choice' => '', 'isCorrect' => false],
                        ['choice' => '', 'isCorrect' => false],
                        ['choice' => '', 'isCorrect' => false],
                    ],
                ];
            }
        } elseif ($existingCount > $cardCount) {
            $this->cards = array_slice($this->cards, 0, $cardCount); // Use $cardCount as an integer
        }
    }


    public function setCorrectChoice($cardIndex, $choiceIndex)
    {
        foreach ($this->cards[$cardIndex]['choices'] as $index => &$choice) {
            $choice['isCorrect'] = $index === $choiceIndex;
        }
    }


    public function store()
    {
        $this->form->cards = $this->cards;

        $this->form->store();

        $this->redirect('/dashboard/decks');
    }

    public function cancel(){
        $this->redirect('/dashboard/decks');
    }

    #[Title('Create Deck')]
    public function render()
    {
        return view('livewire.create-deck');
    }
}
