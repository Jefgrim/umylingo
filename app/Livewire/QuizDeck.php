<?php

namespace App\Livewire;

use App\Models\LearnProgress;
use App\Models\Quiz;
use App\Models\QuizProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
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

    public $correctItems = 0;

    public $achievementTitle;

    public $assessment = false;
    
    public $isReview = false;
    
    public function mount(QuizProgress $quizProgress)
    {
        if (Gate::denies('access-quiz-progress', $quizProgress)) {
            abort(403, 'Unauthorized access');
        }

        if ($quizProgress->deck->isArchived) {
            abort(403, "This Deck is archived.");
        }

        if (!$quizProgress->learnProgress->isCompleted) {
            abort(403, "Complete the lesson first.");
        }

        $this->quizProgress = $quizProgress;
        $this->quiz;
        
        // Check if this is a review (quiz already completed)
        $this->isReview = $quizProgress->isCompleted;
        
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

        $quizProgress->update([
            'totalItems' => $this->quizProgress->deck->cards->count()
        ]);

        if ($this->quizProgress->cardIndex > $this->quizProgress->deck->cards->count() - 1) {
            $quizProgress->update([
                'currentIndex' => 0
            ]);
        }
        if (!$this->quizProgress->isStarted) {
            $quizProgress->update([
                'isStarted' => 1,
                'startedAt' => now(),
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
    public function achievementUnlocked($achievementTitle)
    {
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
                    $this->correctItems++;
                } else {
                    $quiz->update(['isAnswered' => 1]);
                }
            }
            $quizProgress = $this->quizProgress;
            $quizProgress->update([
                'isCompleted' => 1,
                'correctItems' => $this->correctItems,
                'completedAt' => now()
            ]);

            $this->assessment = true;
        }

        $this->dispatch('checkAchievements', quizzes: $this->quizzes, learnId: null);


        // $this->assessments();
    }

    public function assessments()
    {

        $quizzes = $this->quizProgress->quizzes;
    }

    public function setAnswer($choiceId, $quizId)
    {
        $quiz = $this->quizProgress->quizzes->find($quizId);
        $quiz->update(['choice_id' => $choiceId]);

        $this->quizzes = Quiz::where('user_id', Auth::id())
            ->where('quiz_progress_id', $this->quizProgress->id)
            ->get();

        $this->nextQuizCard();
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

        if ($quizProgress->isCompleted) {
            $durationInSeconds = $this->quizProgress->startedAt->diffInSeconds($this->quizProgress->completedAt) ?? null;
            $durationInMinutes = round($this->quizProgress->startedAt->diffInMinutes($this->quizProgress->completedAt), 0) ?? null;
            $correctPercentage = round(($this->quizProgress->correctItems / $this->quizProgress->totalItems) * 100, 2) ?? null;
            $remarks = $correctPercentage >= 70 ? 'Passed' : 'Failed';
        }

        return view('livewire.quiz-deck', [
            'remarks' => $remarks ?? null,
            'correctPercentage' => $correctPercentage ?? null,
            'durationInSeconds' => $durationInSeconds ?? null,
            'durationInMinutes' => $durationInMinutes ?? null,
            'quizzes' => $quizzes,
            'currentQuiz' => $currentQuiz,
            'quizProgress' => $quizProgress
        ]);
    }
    
    public function resetQuizForReview()
    {
        // Reset all quiz answers to allow re-attempting
        $this->quizzes->each(function ($quiz) {
            // Increment attempts to reflect a new review pass
            $quiz->increment('attempt_count');
            $quiz->update([
                'choice_id' => null,
                'isCorrect' => 0,
                'isAnswered' => 0,
            ]);
        });
        
        // Reset quiz progress
        $this->quizProgress->update([
            'currentIndex' => 0,
            'correctItems' => 0,
            'isCompleted' => 0,
            'isStarted' => 1,
            'completedAt' => null,
            'startedAt' => now(),
        ]);
        
        // Refresh the quizzes
        $this->quizzes = Quiz::where('user_id', Auth::id())
            ->where('quiz_progress_id', $this->quizProgress->id)
            ->get();
        
        $this->currentIndex = 0;
        $this->assessment = false;
        $this->isReview = false;
        $this->correctItems = 0;
    }
}
