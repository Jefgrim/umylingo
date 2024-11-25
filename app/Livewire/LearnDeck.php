<?php

namespace App\Livewire;

use App\Models\Deck;
use App\Models\DeckProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class LearnDeck extends Component
{
    public $deckProgress;
    public $currentIndex;

    public function mount(DeckProgress $deckProgress)
    {
        if (Gate::denies('access-deck-progress', $deckProgress)) {
            abort(403, 'Unauthorized access');
        }

        $this->deckProgress = $deckProgress;
        if (!$this->deckProgress->isLearningStarted) {
            $deckProgress->update([
                'isLearningStarted' => 1
            ]);
        }
        $this->currentIndex = $this->deckProgress->cardLearnIndex;
    }

    public function nextCard()
    {
        if ($this->deckProgress->cardLearnIndex < $this->deckProgress->deck->cards->count() - 1) {
            $this->currentIndex = $this->deckProgress->cardLearnIndex + 1;
            $this->saveProgress();
        }
    }

    public function previousCard()
    {
        if ($this->deckProgress->cardLearnIndex  > 0) {
            $this->currentIndex = $this->deckProgress->cardLearnIndex - 1;
            $this->saveProgress();
        }
    }

    private function saveProgress()
    {
        // Save the progress, e.g., to the user's profile or a progress table
        $deckProgress = $this->deckProgress;
        // echo dd($deckProgress->cardLearnIndex);
        $currentIndex = $this->currentIndex;

        $deckProgress->update([
            'cardLearnIndex' => $currentIndex
        ]);

        if ($deckProgress->deck->cards->count() == ($currentIndex + 1)) {
            if (!$deckProgress->isLearningCompleted && $deckProgress->cardLearnIndex == $currentIndex) {
                $deckProgress->update([
                    'cardLearnIndex' => $currentIndex,
                    'isLearningCompleted' => 1
                ]);
            }
        }
    }

    public function render()
    {
        $cards = $this->deckProgress->deck->cards;
        $currentCard = $cards[$this->deckProgress->cardLearnIndex] ?? null;

        // echo dd($currentCard->content);
        return view('livewire.learn-deck', [
            'currentCard' => $currentCard
        ]);
    }
}
