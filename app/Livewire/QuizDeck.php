<?php

namespace App\Livewire;

use App\Models\QuizProgress;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class QuizDeck extends Component
{
    public $quizProgress;
    public $currentIndex;

    public function mount(QuizProgress $quizProgress)
    {
        if (Gate::denies('access-quiz-progress', $quizProgress)) {
            abort(403, 'Unauthorized access');
        }

        $this->quizProgress = $quizProgress;

        if ($this->quizProgress->cardIndex > $this->quizProgress->deck->cards->count() - 1) {
            $quizProgress->update([
                'currentIndex' => 0
            ]);
        }
        if (!$this->quizProgress->isStarted) {
            $quizProgress->update([
                'isStarted' => 1
            ]);
        }
        $this->currentIndex = $this->quizProgress->currentIndex;
    }

    public function nextQuizCard()
    {
        if ($this->quizProgress->currentIndex < $this->quizProgress->deck->cards->count() - 1) {
            $this->currentIndex = $this->quizProgress->currentIndex + 1;
            $this->saveQuizProgress();
        }
    }

    public function previousQuizCard()
    {
        if ($this->quizProgress->currentIndex  > 0) {
            $this->currentIndex = $this->quizProgress->currentIndex - 1;
            $this->saveQuizProgress();
        }
    }
    private function saveQuizProgress()
    {
        // Save the progress, e.g., to the user's profile or a progress table
        $quizProgress = $this->quizProgress;
        // echo dd($quizProgress->currentIndex);
        $currentIndex = $this->currentIndex;

        $quizProgress->update([
            'currentIndex' => $currentIndex
        ]);

        if ($quizProgress->deck->cards->count() == ($currentIndex + 1)) {
            if (!$quizProgress->isCompleted && $quizProgress->currentIndex == $currentIndex) {
                $quizProgress->update([
                    'currentIndex' => $currentIndex,
                ]);
            }
        }
    }

    public function render()
    {
        $cards = $this->quizProgress->deck->cards;
        $quizProgress = $this->quizProgress->quizProgress;
        $currentQuizCard = $cards[$this->quizProgress->currentIndex] ?? null;
        // echo dd($currentCard->content);
        return view('livewire.quiz-deck', [
            'currentQuizCard' => $currentQuizCard,
            'quizProgress' => $quizProgress
        ]);
    }
}
