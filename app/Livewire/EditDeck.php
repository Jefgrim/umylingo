<?php

namespace App\Livewire;

use App\Livewire\Forms\DeckForm;
use App\Models\Deck;
use Livewire\Component;

class EditDeck extends AdminComponent
{
    public Deck $deck;
    public $cardCount;
    public DeckForm $form;
    public $cards = []; // Store card data
    public $deletedCardIds = []; // Track IDs of deleted cards

    public function mount(Deck $deck)
    {
        $this->deck = $deck;
        $this->form->setDeck($deck);
        $this->cards = $this->form->cards; // Initialize cards for editing
        $this->cardCount = count($this->cards);
    }

    public function addCard()
    {
        // Add a new card structure with initialized values
        $this->cards[] = [
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
        $this->cardCount++;
    }

    public function removeCard($index)
    {
        // If the card has an ID, add it to the deleted list
        if (isset($this->cards[$index]['id'])) {
            $this->deletedCardIds[] = $this->cards[$index]['id'];
        }

        // Remove the card from the cards array
        array_splice($this->cards, $index, 1);
        $this->cardCount--;
    }

    public function setCorrectChoice($cardIndex, $choiceIndex)
    {
        foreach ($this->cards[$cardIndex]['choices'] as $index => &$choice) {
            $choice['isCorrect'] = $index === $choiceIndex;
        }
    }

    public function update()
    {
        // Ensure at least one card is present
        if (count($this->cards) === 0) {
            $this->addError('cards', 'You must add at least one card.');
            return;
        }
        // Pass cards and deleted IDs to the form
        $this->form->cards = $this->cards;
        $this->form->deletedCardIds = $this->deletedCardIds;
        $this->form->update();
        $this->redirect('/dashboard/decks');
    }

    public function toggleArchive()
    {
        $this->form->toggleArchive();
        $this->redirect('/dashboard/decks');
    }

    public function render()
    {
        return view('livewire.edit-deck');
    }
}
