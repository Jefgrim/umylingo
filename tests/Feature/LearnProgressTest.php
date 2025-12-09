<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Deck;
use App\Models\LearnProgress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LearnProgressTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $deck;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create([
            'isAdmin' => false,
        ]);

        $this->deck = Deck::factory()->create();
    }

    public function test_learn_progress_can_be_created()
    {
        $learnProgress = LearnProgress::create([
            'user_id' => $this->user->id,
            'deck_id' => $this->deck->id,
            'currentIndex' => 0,
            'isStarted' => false,
            'isCompleted' => false,
        ]);

        $this->assertDatabaseHas('learn_progress', [
            'user_id' => $this->user->id,
            'deck_id' => $this->deck->id,
            'currentIndex' => 0,
        ]);
    }

    public function test_starting_learn_session_sets_started_flag()
    {
        $learnProgress = LearnProgress::create([
            'user_id' => $this->user->id,
            'deck_id' => $this->deck->id,
        ]);

        $learnProgress->update([
            'isStarted' => true,
            'startedAt' => now(),
        ]);

        $this->assertEquals(1, $learnProgress->fresh()->isStarted);
        $this->assertNotNull($learnProgress->fresh()->startedAt);
    }

    public function test_current_index_tracks_progress_through_deck()
    {
        $learnProgress = LearnProgress::create([
            'user_id' => $this->user->id,
            'deck_id' => $this->deck->id,
            'currentIndex' => 0,
            'isStarted' => true,
        ]);

        // Simulate progressing through cards
        for ($i = 1; $i <= 5; $i++) {
            $learnProgress->update(['currentIndex' => $i]);
            $this->assertEquals($i, $learnProgress->fresh()->currentIndex);
        }
    }

    public function test_completing_deck_sets_completion_flag()
    {
        $learnProgress = LearnProgress::create([
            'user_id' => $this->user->id,
            'deck_id' => $this->deck->id,
            'isStarted' => true,
            'isCompleted' => false,
        ]);

        $learnProgress->update([
            'isCompleted' => true,
            'completedAt' => now(),
        ]);

        $this->assertEquals(1, $learnProgress->fresh()->isCompleted);
        $this->assertNotNull($learnProgress->fresh()->completedAt);
    }

    public function test_user_can_have_multiple_learn_progresses()
    {
        $deck2 = Deck::factory()->create();
        $deck3 = Deck::factory()->create();

        LearnProgress::create([
            'user_id' => $this->user->id,
            'deck_id' => $this->deck->id,
            'isCompleted' => true,
        ]);

        LearnProgress::create([
            'user_id' => $this->user->id,
            'deck_id' => $deck2->id,
            'isCompleted' => false,
        ]);

        LearnProgress::create([
            'user_id' => $this->user->id,
            'deck_id' => $deck3->id,
            'isCompleted' => true,
        ]);

        $this->assertEquals(3, $this->user->learnProgress()->count());
        $this->assertEquals(2, $this->user->learnProgress()->where('isCompleted', true)->count());
    }

    public function test_learn_progress_belongs_to_user_and_deck()
    {
        $learnProgress = LearnProgress::create([
            'user_id' => $this->user->id,
            'deck_id' => $this->deck->id,
        ]);

        $this->assertEquals($this->user->id, $learnProgress->user->id);
        $this->assertEquals($this->deck->id, $learnProgress->deck->id);
    }

    public function test_learn_progress_can_be_restarted()
    {
        $learnProgress = LearnProgress::create([
            'user_id' => $this->user->id,
            'deck_id' => $this->deck->id,
            'currentIndex' => 10,
            'isStarted' => true,
            'isCompleted' => true,
            'completedAt' => now()->subDay(),
        ]);

        // Restart the progress
        $learnProgress->update([
            'currentIndex' => 0,
            'isCompleted' => false,
            'completedAt' => null,
            'startedAt' => now(),
        ]);

        $this->assertEquals(0, $learnProgress->fresh()->currentIndex);
        $this->assertEquals(0, $learnProgress->fresh()->isCompleted);
        $this->assertNull($learnProgress->fresh()->completedAt);
    }

    public function test_timestamps_are_set_correctly()
    {
        $startTime = now();

        $learnProgress = LearnProgress::create([
            'user_id' => $this->user->id,
            'deck_id' => $this->deck->id,
            'isStarted' => true,
            'startedAt' => $startTime,
        ]);

        $this->assertNotNull($learnProgress->startedAt);

        $completionTime = now()->addHour();
        $learnProgress->update([
            'isCompleted' => true,
            'completedAt' => $completionTime,
        ]);

        $this->assertNotNull($learnProgress->fresh()->completedAt);
        $this->assertTrue($learnProgress->fresh()->completedAt > $learnProgress->startedAt);
    }
}
