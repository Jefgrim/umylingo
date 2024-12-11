<?php

namespace App\Livewire;

use App\Models\QuizProgress;
use App\Models\UserAchievement;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class CheckAchievements extends Component
{

    public $userAchievements;

    #[On('checkAchievements')]
    public function checkAchievement($quizzes = null, $learnId = null)
    {
        $this->userAchievements = UserAchievement::where('user_id', Auth::id())->get();

        $userAchievements = $this->userAchievements;

        // First Steps achievement check
        if (!$userAchievements[0]->achieved_at) {
            $learnProgresses = Auth::user()->learnProgress;
            if ($learnProgresses->where('isCompleted', 1)->count() == 1) {
                $userAchievements[0]->update(['achieved_at' => now()]);
                $this->dispatch('achievementUnlocked', achievementTitle: $userAchievements[0]->achievement->achievement_title);
            }
        }

        // Quiz Master achievement check
        if ($quizzes) {
            if (!$userAchievements[1]->achieved_at) {
                $totalItems = count($quizzes);
                $correctItems = count(array_filter($quizzes, fn($quiz) => $quiz['isCorrect']));

                if ($totalItems == $correctItems) {
                    $userAchievements[1]->update(['achieved_at' => now()]);
                    $this->dispatch('achievementUnlocked', achievementTitle: $userAchievements[0]->achievement->achievement_title);
                }
            }
        }

        // Language Enthusiast check
        if (!$userAchievements[2]->achieved_at) {
            $learnProgresses = Auth::user()->learnProgress;
            if ($learnProgresses->where('isCompleted', 1)->count() >= 5) {
                $userAchievements[2]->update(['achieved_at' => now()]);
                $this->dispatch('achievementUnlocked', achievementTitle: $userAchievements[2]->achievement->achievement_title);
            }
        }

        // Quiz Conqueror check
        if (!$userAchievements[3]->achieved_at) {
            $quizProgresses = QuizProgress::where('user_id', Auth::id())->get();
            $totalCorrectItems = 0;
            $totalItems = 0;

            if ($quizProgresses->where('isCompleted', 1)->count() > 5) {
                $quizProgresses = QuizProgress::where('user_id', Auth::id())->where('isCompleted', 1)->get();
                $quizzesAbove80PercentCount = 0;

                foreach ($quizProgresses as $quizProgress) {
                    $correctPercentage = ($quizProgress->correctItems / $quizProgress->totalItems) * 100;
                    if ($correctPercentage >= 80) {
                        $quizzesAbove80PercentCount++;
                    }
                }

                if ($quizzesAbove80PercentCount >= 5) {
                    $userAchievements[3]->update(['achieved_at' => now()]);
                    $this->dispatch('achievementUnlocked', achievementTitle: $userAchievements[3]->achievement->achievement_title);
                }
            }
        }
    }
}
