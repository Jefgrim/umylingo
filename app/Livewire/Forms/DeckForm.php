<?php

namespace App\Livewire\Forms;

use App\Models\Achievement;
use App\Models\Choice;
use App\Models\Deck;
use Livewire\Attributes\Validate;
use Livewire\Form;

class DeckForm extends Form
{
    #[Validate('required|min:1')]
    public $cards = [];
    #[Validate('required')]
    public $language;
    #[Validate('required')]
    public $deck_description;
    #[Validate('required')]
    public $achievement_title;
    #[Validate('required')]
    public $achievement_description;

    public $deletedCardIds = []; // Track IDs of deleted cards
    public Deck $deck;

    public function store()
    {
        $this->validate();

        $deck = Deck::create($this->only(['language', 'deck_description']));
        $deck->achievement()->create($this->only('achievement_title', 'achievement_description'));

        foreach ($this->cards as $cardData) {
            $card = $deck->cards()->create([
                'content' => $cardData['content'],
                'question' => $cardData['question'],
                'answer' => $cardData['answer'],
            ]);

            foreach ($cardData['choices'] as $choiceData) {
                $card->choices()->create([
                    'choice' => $choiceData['choice'],
                    'isCorrect' => $choiceData['isCorrect'] ? 1 : 0,
                ]);
            }
        }
    }

    public function setDeck(Deck $deck)
    {
        $this->deck = $deck;
        $this->language = $deck->language;
        $this->deck_description = $deck->deck_description;
        $this->achievement_title = $deck->achievement->achievement_title;
        $this->achievement_description = $deck->achievement->achievement_description;
        $this->cards = $deck->cards->map(function ($card) {
            return [
                'id' => $card->id,
                'content' => $card->content,
                'question' => $card->question,
                'answer' => $card->answer,
                'choices' => $card->choices->map(fn($choice) => [
                    'id' => $choice->id,
                    'choice' => $choice->choice,
                    'isCorrect' => $choice->isCorrect,
                ])->toArray(),
            ];
        })->toArray();
    }

    public function toggleArchive()
{
    $this->deck->update(['isArchived' => $this->deck->isArchived ? null : now()]);
}


    public function update()
    {
        $this->validate();

        $this->deck->update($this->only('language', 'deck_description'));
        $this->deck->achievement->update($this->only('achievement_title', 'achievement_description'));

        // Handle deleted cards
        if (!empty($this->deletedCardIds)) {
            $this->deck->cards()->whereIn('id', $this->deletedCardIds)->delete();
        }

        // Handle updating or creating cards
        foreach ($this->cards as $cardData) {
            if (isset($cardData['id'])) {
                $card = $this->deck->cards()->find($cardData['id']);
                $card->update([
                    'content' => $cardData['content'],
                    'question' => $cardData['question'],
                    'answer' => $cardData['answer'],
                ]);

                foreach ($cardData['choices'] as $choiceData) {
                    if (isset($choiceData['id'])) {
                        $choice = Choice::find($choiceData['id']);
                        $choice->update([
                            'choice' => $choiceData['choice'],
                            'isCorrect' => $choiceData['isCorrect'] ? 1 : 0,
                        ]);
                    } else {
                        $card->choices()->create([
                            'choice' => $choiceData['choice'],
                            'isCorrect' => $choiceData['isCorrect'] ? 1 : 0,
                        ]);
                    }
                }
            } else {
                $newCard = $this->deck->cards()->create([
                    'content' => $cardData['content'],
                    'question' => $cardData['question'],
                    'answer' => $cardData['answer'],
                ]);

                foreach ($cardData['choices'] as $choiceData) {
                    $newCard->choices()->create([
                        'choice' => $choiceData['choice'],
                        'isCorrect' => $choiceData['isCorrect'] ? 1 : 0,
                    ]);
                }
            }
        }
    }
}
