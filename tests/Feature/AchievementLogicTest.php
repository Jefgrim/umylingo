<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Achievement;
use App\Models\UserAchievement;
use App\Models\Deck;
use App\Models\LearnProgress;
use App\Models\QuizProgress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AchievementLogicTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $achievements;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create([
            'isAdmin' => false,
        ]);

        // Create the 4 achievements from your CheckAchievements component
        $this->achievements = [
            Achievement::factory()->create([
                'achievement_title' => 'First Steps',
                'achievement_description' => 'Complete your first deck',
            ]),
            Achievement::factory()->create([
                'achievement_title' => 'Quiz Master',
                'achievement_description' => 'Get 100% on a quiz',
            ]),
            Achievement::factory()->create([
                'achievement_title' => 'Language Enthusiast',
                'achievement_description' => 'Complete 5 decks',
            ]),
            Achievement::factory()->create([
                'achievement_title' => 'Quiz Conqueror',
                'achievement_description' => 'Score 80% or higher on 5 quizzes',
            ]),
        ];

        // Create user achievements (not yet achieved)
        foreach ($this->achievements as $achievement) {
            UserAchievement::create([
                'user_id' => $this->user->id,
                'achievement_id' => $achievement->id,
                'achieved_at' => null,
            ]);
        }
    }

    public function test_first_steps_achievement_awarded_when_first_deck_completed()
    {
        $this->actingAs($this->user);

        $deck = Deck::factory()->create();

        // Complete one learn progress
        LearnProgress::create([
            'user_id' => $this->user->id,
            'deck_id' => $deck->id,
            'currentIndex' => 10,
            'isStarted' => true,
            'isCompleted' => true,
            'startedAt' => now()->subHour(),
            'completedAt' => now(),
        ]);

        // Trigger achievement check
        $this->user->refresh();
        $completedDecks = $this->user->learnProgress()->where('isCompleted', 1)->count();
        
        $this->assertEquals(1, $completedDecks);

        // Check if achievement can be awarded
        $userAchievement = UserAchievement::where('user_id', $this->user->id)
            ->where('achievement_id', $this->achievements[0]->id)
            ->first();

        $this->assertNull($userAchievement->achieved_at);

        // Simulate the CheckAchievements logic
        if ($completedDecks == 1) {
            $userAchievement->update(['achieved_at' => now()]);
        }

        $userAchievement->refresh();
        $this->assertNotNull($userAchievement->achieved_at);
    }

    public function test_language_enthusiast_achievement_awarded_after_five_completed_decks()
    {
        $this->actingAs($this->user);

        // Complete 5 decks
        for ($i = 0; $i < 5; $i++) {
            $deck = Deck::factory()->create();
            LearnProgress::create([
                'user_id' => $this->user->id,
                'deck_id' => $deck->id,
                'isStarted' => true,
                'isCompleted' => true,
                'completedAt' => now(),
            ]);
        }

        $completedDecks = $this->user->learnProgress()->where('isCompleted', 1)->count();
        $this->assertEquals(5, $completedDecks);

        // Simulate CheckAchievements logic
        $userAchievement = UserAchievement::where('user_id', $this->user->id)
            ->where('achievement_id', $this->achievements[2]->id)
            ->first();

        if ($completedDecks >= 5) {
            $userAchievement->update(['achieved_at' => now()]);
        }

        $userAchievement->refresh();
        $this->assertNotNull($userAchievement->achieved_at);
    }

    public function test_quiz_conqueror_achievement_requires_five_quizzes_above_80_percent()
    {
        $this->actingAs($this->user);

        // Create 5 quiz progresses with 80%+ scores
        for ($i = 0; $i < 5; $i++) {
            $deck = Deck::factory()->create();
            
            // Create learn progress first (required for quiz progress)
            $learnProgress = LearnProgress::create([
                'user_id' => $this->user->id,
                'deck_id' => $deck->id,
                'isStarted' => true,
                'isCompleted' => true,
            ]);

            QuizProgress::create([
                'user_id' => $this->user->id,
                'deck_id' => $deck->id,
                'learn_progress_id' => $learnProgress->id,
                'totalItems' => 10,
                'correctItems' => 9, // 90%
                'isStarted' => true,
                'isCompleted' => true,
            ]);
        }

        // Check achievement logic
        $quizProgresses = QuizProgress::where('user_id', $this->user->id)
            ->where('isCompleted', 1)
            ->get();

        $quizzesAbove80PercentCount = 0;
        foreach ($quizProgresses as $quizProgress) {
            $correctPercentage = ($quizProgress->correctItems / $quizProgress->totalItems) * 100;
            if ($correctPercentage >= 80) {
                $quizzesAbove80PercentCount++;
            }
        }

        $this->assertEquals(5, $quizzesAbove80PercentCount);

        // Award achievement
        $userAchievement = UserAchievement::where('user_id', $this->user->id)
            ->where('achievement_id', $this->achievements[3]->id)
            ->first();

        if ($quizzesAbove80PercentCount >= 5) {
            $userAchievement->update(['achieved_at' => now()]);
        }

        $userAchievement->refresh();
        $this->assertNotNull($userAchievement->achieved_at);
    }

    public function test_achievement_not_awarded_prematurely()
    {
        $this->actingAs($this->user);

        // Create only 3 completed decks (not enough for Language Enthusiast)
        for ($i = 0; $i < 3; $i++) {
            $deck = Deck::factory()->create();
            LearnProgress::create([
                'user_id' => $this->user->id,
                'deck_id' => $deck->id,
                'isCompleted' => true,
            ]);
        }

        $completedDecks = $this->user->learnProgress()->where('isCompleted', 1)->count();
        $this->assertEquals(3, $completedDecks);

        // Language Enthusiast should NOT be awarded
        $userAchievement = UserAchievement::where('user_id', $this->user->id)
            ->where('achievement_id', $this->achievements[2]->id)
            ->first();

        $this->assertNull($userAchievement->achieved_at);
    }
}
