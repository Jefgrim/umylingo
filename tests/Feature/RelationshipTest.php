<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Deck;
use App\Models\Card;
use App\Models\Choice;
use App\Models\Achievement;
use App\Models\UserAchievement;
use App\Models\LearnProgress;
use App\Models\Quiz;
use App\Models\QuizProgress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_many_achievements()
    {
        $user = User::factory()->create();
        $achievement1 = Achievement::factory()->create();
        $achievement2 = Achievement::factory()->create();

        UserAchievement::create([
            'user_id' => $user->id,
            'achievement_id' => $achievement1->id,
            'achieved_at' => now(),
        ]);

        UserAchievement::create([
            'user_id' => $user->id,
            'achievement_id' => $achievement2->id,
            'achieved_at' => null,
        ]);

        $this->assertEquals(2, $user->userAchievements()->count());
    }

    public function test_user_has_many_learn_progresses()
    {
        $user = User::factory()->create();
        $deck1 = Deck::factory()->create();
        $deck2 = Deck::factory()->create();

        LearnProgress::create([
            'user_id' => $user->id,
            'deck_id' => $deck1->id,
        ]);

        LearnProgress::create([
            'user_id' => $user->id,
            'deck_id' => $deck2->id,
        ]);

        $this->assertEquals(2, $user->learnProgress()->count());
    }

    public function test_user_has_many_quizzes()
    {
        $user = User::factory()->create();
        $deck = Deck::factory()->create();
        $card = Card::create([
            'deck_id' => $deck->id,
            'content' => 'Test',
            'question' => 'Test?',
        ]);

        $learnProgress = LearnProgress::create([
            'user_id' => $user->id,
            'deck_id' => $deck->id,
            'isCompleted' => true,
        ]);

        $quizProgress = QuizProgress::create([
            'user_id' => $user->id,
            'deck_id' => $deck->id,
            'learn_progress_id' => $learnProgress->id,
        ]);

        Quiz::create([
            'quiz_progress_id' => $quizProgress->id,
            'user_id' => $user->id,
            'card_id' => $card->id,
        ]);

        $this->assertEquals(1, $user->quizzes()->count());
    }

    public function test_deck_has_many_cards()
    {
        $deck = Deck::factory()->create();

        Card::create([
            'deck_id' => $deck->id,
            'content' => 'Card 1',
            'question' => 'Question 1?',
        ]);

        Card::create([
            'deck_id' => $deck->id,
            'content' => 'Card 2',
            'question' => 'Question 2?',
        ]);

        $this->assertEquals(2, $deck->cards()->count());
    }

    public function test_deck_has_many_learn_progresses()
    {
        $deck = Deck::factory()->create();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        LearnProgress::create([
            'user_id' => $user1->id,
            'deck_id' => $deck->id,
        ]);

        LearnProgress::create([
            'user_id' => $user2->id,
            'deck_id' => $deck->id,
        ]);

        $this->assertEquals(2, $deck->learnProgress()->count());
    }

    public function test_card_belongs_to_deck()
    {
        $deck = Deck::factory()->create();
        $card = Card::create([
            'deck_id' => $deck->id,
            'content' => 'Test',
            'question' => 'Test?',
        ]);

        $this->assertInstanceOf(Deck::class, $card->deck);
        $this->assertEquals($deck->id, $card->deck->id);
    }

    public function test_card_has_many_choices()
    {
        $deck = Deck::factory()->create();
        $card = Card::create([
            'deck_id' => $deck->id,
            'content' => 'Test',
            'question' => 'Test?',
        ]);

        Choice::create([
            'card_id' => $card->id,
            'choice' => 'Answer 1',
            'isCorrect' => true,
        ]);

        Choice::create([
            'card_id' => $card->id,
            'choice' => 'Answer 2',
            'isCorrect' => false,
        ]);

        $this->assertEquals(2, $card->choices()->count());
    }

    public function test_card_has_many_quizzes()
    {
        $user = User::factory()->create();
        $deck = Deck::factory()->create();
        $card = Card::create([
            'deck_id' => $deck->id,
            'content' => 'Test',
            'question' => 'Test?',
        ]);

        $learnProgress = LearnProgress::create([
            'user_id' => $user->id,
            'deck_id' => $deck->id,
            'isCompleted' => true,
        ]);

        $quizProgress = QuizProgress::create([
            'user_id' => $user->id,
            'deck_id' => $deck->id,
            'learn_progress_id' => $learnProgress->id,
        ]);

        Quiz::create([
            'quiz_progress_id' => $quizProgress->id,
            'user_id' => $user->id,
            'card_id' => $card->id,
        ]);

        $this->assertEquals(1, $card->quizzes()->count());
    }

    public function test_choice_belongs_to_card()
    {
        $deck = Deck::factory()->create();
        $card = Card::create([
            'deck_id' => $deck->id,
            'content' => 'Test',
            'question' => 'Test?',
        ]);

        $choice = Choice::create([
            'card_id' => $card->id,
            'choice' => 'Answer',
            'isCorrect' => true,
        ]);

        $this->assertInstanceOf(Card::class, $choice->card);
        $this->assertEquals($card->id, $choice->card->id);
    }

    public function test_quiz_belongs_to_card_and_user()
    {
        $user = User::factory()->create();
        $deck = Deck::factory()->create();
        $card = Card::create([
            'deck_id' => $deck->id,
            'content' => 'Test',
            'question' => 'Test?',
        ]);

        $learnProgress = LearnProgress::create([
            'user_id' => $user->id,
            'deck_id' => $deck->id,
            'isCompleted' => true,
        ]);

        $quizProgress = QuizProgress::create([
            'user_id' => $user->id,
            'deck_id' => $deck->id,
            'learn_progress_id' => $learnProgress->id,
        ]);

        $quiz = Quiz::create([
            'quiz_progress_id' => $quizProgress->id,
            'user_id' => $user->id,
            'card_id' => $card->id,
        ]);

        $this->assertInstanceOf(User::class, $quiz->user);
        $this->assertInstanceOf(Card::class, $quiz->card);
        $this->assertEquals($user->id, $quiz->user->id);
        $this->assertEquals($card->id, $quiz->card->id);
    }

    public function test_quiz_progress_belongs_to_user_and_deck()
    {
        $user = User::factory()->create();
        $deck = Deck::factory()->create();

        $learnProgress = LearnProgress::create([
            'user_id' => $user->id,
            'deck_id' => $deck->id,
            'isCompleted' => true,
        ]);

        $quizProgress = QuizProgress::create([
            'user_id' => $user->id,
            'deck_id' => $deck->id,
            'learn_progress_id' => $learnProgress->id,
        ]);

        $this->assertInstanceOf(User::class, $quizProgress->user);
        $this->assertInstanceOf(Deck::class, $quizProgress->deck);
        $this->assertInstanceOf(LearnProgress::class, $quizProgress->learnProgress);
    }

    public function test_learn_progress_belongs_to_user_and_deck()
    {
        $user = User::factory()->create();
        $deck = Deck::factory()->create();

        $learnProgress = LearnProgress::create([
            'user_id' => $user->id,
            'deck_id' => $deck->id,
        ]);

        $this->assertInstanceOf(User::class, $learnProgress->user);
        $this->assertInstanceOf(Deck::class, $learnProgress->deck);
    }

    public function test_learn_progress_has_one_quiz_progress()
    {
        $user = User::factory()->create();
        $deck = Deck::factory()->create();

        $learnProgress = LearnProgress::create([
            'user_id' => $user->id,
            'deck_id' => $deck->id,
            'isCompleted' => true,
        ]);

        $quizProgress = QuizProgress::create([
            'user_id' => $user->id,
            'deck_id' => $deck->id,
            'learn_progress_id' => $learnProgress->id,
        ]);

        $this->assertInstanceOf(QuizProgress::class, $learnProgress->quizProgress);
        $this->assertEquals($quizProgress->id, $learnProgress->quizProgress->id);
    }

    public function test_achievement_has_many_user_achievements()
    {
        $achievement = Achievement::factory()->create();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        UserAchievement::create([
            'user_id' => $user1->id,
            'achievement_id' => $achievement->id,
        ]);

        UserAchievement::create([
            'user_id' => $user2->id,
            'achievement_id' => $achievement->id,
        ]);

        $this->assertEquals(2, $achievement->userAchievements()->count());
    }
}
