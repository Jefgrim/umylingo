<?php

namespace App\Livewire;

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
            foreach ($learnProgresses as $learnProgress) {
                if ($learnProgress->isCompleted) {
                    $userAchievements[0]->update(['achieved_at' => now()]);
                    $this->dispatch('achievementUnlocked', achievementTitle: $userAchievements[0]->achievement->achievement_title);
                    break;
                }
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

        // Level Up achievement check
        if (!$userAchievements[0]->achieved_at) {
            $learnProgresses = Auth::user()->learnProgress;
            foreach ($learnProgresses as $learnProgress) {
                if ($learnProgress->isCompleted) {
                    $userAchievements[0]->update(['achieved_at' => now()]);
                    break;
                }
            }
        }

        // Language Enthusiast achievement check
        if (!$userAchievements[0]->achieved_at) {
            $learnProgresses = Auth::user()->learnProgress;
            foreach ($learnProgresses as $learnProgress) {
                if ($learnProgress->isCompleted) {
                    $userAchievements[0]->update(['achieved_at' => now()]);
                    break;
                }
            }
        }

        // Quiz Conqueror achievement check
        if (!$userAchievements[0]->achieved_at) {
            $learnProgresses = Auth::user()->learnProgress;
            foreach ($learnProgresses as $learnProgress) {
                if ($learnProgress->isCompleted) {
                    $userAchievements[0]->update(['achieved_at' => now()]);
                    break;
                }
            }
        }

        // Language Prodigy achievement check
        if (!$userAchievements[0]->achieved_at) {
            $learnProgresses = Auth::user()->learnProgress;
            foreach ($learnProgresses as $learnProgress) {
                if ($learnProgress->isCompleted) {
                    $userAchievements[0]->update(['achieved_at' => now()]);
                    break;
                }
            }
        }
    }
}
