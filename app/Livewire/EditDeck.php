<?php

namespace App\Livewire;

use App\Livewire\Forms\DeckForm;
use App\Models\Deck;
use Livewire\Component;


class EditDeck extends AdminComponent
{
    public DeckForm $form;

    public function mount(Deck $deck)
    {
        $this->form->setDeck($deck);
    }
    public function update()
    {
        $this->form->update();

        $this->redirect('/dashboard/decks');
    }
    public function render()
    {
        return view('livewire.edit-deck');
    }
}
