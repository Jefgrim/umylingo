<?php

namespace App\Livewire;

use App\Models\LearnProgress;
use App\Models\UserAchievement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class LearnDeck extends Component
{
    public $learnProgress;
    public $currentIndex;
    public $achievementTitle;

    #[On('achievementUnlocked')]
    public function achievementUnlocked($achievementTitle)
    {
        $this->achievementTitle = $achievementTitle;
    }
    
    public function mount(LearnProgress $learnProgress)
    {
        if (Gate::denies('access-learn-progress', $learnProgress)) {
            abort(403, 'Unauthorized access');
        }

        if($learnProgress->deck->isArchived){
            abort(403, "This Deck is archived.");
        }

        $this->learnProgress = $learnProgress;

        if ($this->learnProgress->currentIndex > $this->learnProgress->deck->cards->count() - 1) {
            $learnProgress->update([
                'currentIndex' => 0
            ]);
        }

        if ($this->learnProgress->currentIndex == $this->learnProgress->deck->cards->count() - 1) {
            $learnProgress->update([
                'isCompleted' => 1
            ]);
        }

        if ($this->learnProgress->currentIndex > $this->learnProgress->deck->cards->count() - 1) {
            $learnProgress->update([
                'currentIndex' => 0
            ]);
        }

        if (!$this->learnProgress->isStarted) {
            $learnProgress->update([
                'isStarted' => 1
            ]);
        }
        $this->currentIndex = $this->learnProgress->currentIndex;
    }

    public function nextLearnCard()
    {
        if ($this->learnProgress->currentIndex < $this->learnProgress->deck->cards->count() - 1) {
            $this->currentIndex = $this->learnProgress->currentIndex + 1;
            $this->saveLearnProgress();
        }
    }

    public function previousLearnCard()
    {
        if ($this->learnProgress->currentIndex  > 0) {
            $this->currentIndex = $this->learnProgress->currentIndex - 1;
            $this->saveLearnProgress();
        }
    }
    private function saveLearnProgress()
    {
        // Save the progress, e.g., to the user's profile or a progress table
        $learnProgress = $this->learnProgress;
        // echo dd($learnProgress->currentIndex);
        $currentIndex = $this->currentIndex;

        $learnProgress->update([
            'currentIndex' => $currentIndex
        ]);

        if ($learnProgress->deck->cards->count() == ($currentIndex + 1)) {
            if (!$learnProgress->isCompleted && $learnProgress->currentIndex == $currentIndex) {
                $learnProgress->update([
                    'currentIndex' => $currentIndex,
                    'isCompleted' => 1
                ]);
            }

            $this->dispatch('checkAchievements');
        }
    }

    public function render()
    {
        $cards = $this->learnProgress->deck->cards;
        $quizProgress = $this->learnProgress->quizProgress;
        $currentLearnCard = $cards[$this->learnProgress->currentIndex] ?? null;
        // echo dd($currentCard->content);
        return view('livewire.learn-deck', [
            'currentLearnCard' => $currentLearnCard,
            'quizProgress' => $quizProgress
        ]);
    }
}
