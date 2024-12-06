<?php

namespace App\Livewire;

use App\Models\LearnProgress;
use App\Models\Quiz;
use App\Models\QuizProgress;
use App\Models\UserAchievement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class QuizDeck extends Component
{
    public $quizProgress;
    public $currentIndex;

    public $answers = [];

    public $quizzes;

    public $quiz;

    public $isUnAnswered = false;

    public $achievementTitle;
    public function mount(QuizProgress $quizProgress)
    {
        if (Gate::denies('access-quiz-progress', $quizProgress)) {
            abort(403, 'Unauthorized access');
        }

        $this->quizProgress = $quizProgress;
        $this->quiz;
        $cards = $this->quizProgress->deck->cards;
        $shuffledCards = $cards->shuffle();

        foreach ($shuffledCards as $card) {
            Quiz::firstOrCreate([
                'quiz_progress_id' => $quizProgress->id,
                'user_id' => Auth::id(),
                'card_id' => $card->id,
            ]);
        }

        $this->quizzes = Quiz::where('user_id', Auth::id())->where('quiz_progress_id', $quizProgress->id)->get();

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
    #[On('achievementUnlocked')]
    public function achievementUnlocked($achievementTitle){
        $this->achievementTitle = $achievementTitle;
    }

    public function finishQuiz()
    {
        $this->isUnAnswered = false;
        $quizzes = $this->quizzes;
        foreach ($quizzes as $quiz) {
            if ($quiz->choice_id == null) {
                $this->isUnAnswered = true;
                break;
            }
        }

        if ($this->isUnAnswered == false) {
            foreach ($quizzes as $quiz) {
                if ($quiz->choice->isCorrect) {
                    $quiz->update(['isCorrect' => 1, 'isAnswered' => 1]);
                } else {
                    $quiz->update(['isAnswered' => 1]);
                }
            }
            $quizProgress = $this->quizProgress;
            $quizProgress->update(['isCompleted' => 1]);
        }

        $this->dispatch('checkAchievements', quizzes: $this->quizzes, learnId: null);
    }
    public function setAnswer($choiceId, $quizId)
    {
        $quiz = $this->quizProgress->quizzes->find($quizId);
        $quiz->update(['choice_id' => $choiceId]);

        $this->quizzes = Quiz::where('user_id', Auth::id())
            ->where('quiz_progress_id', $this->quizProgress->id)
            ->get();
    }
    private function saveQuizProgress()
    {
        $quizProgress = $this->quizProgress;
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
        $quizzes = $this->quizzes;
        $quizProgress = $this->quizProgress;
        $currentQuiz = $quizzes[$this->quizProgress->currentIndex] ?? null;
        return view('livewire.quiz-deck', [
            'currentQuiz' => $currentQuiz,
            'quizProgress' => $quizProgress
        ]);
    }
}
