<?php

namespace App\Livewire;

use App\Livewire\Forms\DeckForm;
use Livewire\Attributes\Title;
use Livewire\Component;

class CreateDeck extends AdminComponent
{
    public DeckForm $form;

    public function create()
    {
        $this->form->create();

        $this->redirect('/dashboard/decks');
    }
    
    #[Title('Create Deck')]
    public function render()
    {
        return view('livewire.create-deck');
    }
}
