<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Deck;
use App\Models\Card;
use App\Models\QuizProgress;
use App\Models\LearnProgress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardStatsTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create([
            'isAdmin' => true,
        ]);
    }

    public function test_dashboard_displays_total_user_count()
    {
        $this->actingAs($this->admin);

        // Create regular users
        User::factory()->count(5)->create(['isAdmin' => false]);

        Livewire::test('dashboard')
            ->assertSet('currentUsers', 5);
    }

    public function test_dashboard_excludes_admins_from_user_count()
    {
        $this->actingAs($this->admin);

        User::factory()->count(3)->create(['isAdmin' => false]);
        User::factory()->count(2)->create(['isAdmin' => true]);

        Livewire::test('dashboard')
            ->assertSet('currentUsers', 3);
    }

    public function test_dashboard_displays_total_decks_count()
    {
        $this->actingAs($this->admin);

        Deck::factory()->count(10)->create();

        Livewire::test('dashboard')
            ->assertSet('totalDecks', 10);
    }

    public function test_dashboard_displays_total_cards_count()
    {
        $this->actingAs($this->admin);

        $deck1 = Deck::factory()->create();
        $deck2 = Deck::factory()->create();

        Card::factory()->count(5)->create(['deck_id' => $deck1->id]);
        Card::factory()->count(7)->create(['deck_id' => $deck2->id]);

        Livewire::test('dashboard')
            ->assertSet('totalCards', 12);
    }

    public function test_dashboard_displays_started_quizzes_count()
    {
        $this->actingAs($this->admin);

        $user = User::factory()->create(['isAdmin' => false]);
        $deck = Deck::factory()->create();

        $learnProgress = LearnProgress::create([
            'user_id' => $user->id,
            'deck_id' => $deck->id,
            'isCompleted' => true,
        ]);

        QuizProgress::create([
            'user_id' => $user->id,
            'deck_id' => $deck->id,
            'learn_progress_id' => $learnProgress->id,
            'isStarted' => true,
            'isCompleted' => false,
        ]);

        QuizProgress::create([
            'user_id' => $user->id,
            'deck_id' => $deck->id,
            'learn_progress_id' => $learnProgress->id,
            'isStarted' => true,
            'isCompleted' => true,
        ]);

        Livewire::test('dashboard')
            ->assertSet('totalQuizzesStarted', 2);
    }

    public function test_dashboard_displays_completed_quizzes_count()
    {
        $this->actingAs($this->admin);

        $user = User::factory()->create(['isAdmin' => false]);
        $deck = Deck::factory()->create();

        $learnProgress = LearnProgress::create([
            'user_id' => $user->id,
            'deck_id' => $deck->id,
            'isCompleted' => true,
        ]);

        QuizProgress::create([
            'user_id' => $user->id,
            'deck_id' => $deck->id,
            'learn_progress_id' => $learnProgress->id,
            'isStarted' => true,
            'isCompleted' => true,
        ]);

        QuizProgress::create([
            'user_id' => $user->id,
            'deck_id' => $deck->id,
            'learn_progress_id' => $learnProgress->id,
            'isStarted' => true,
            'isCompleted' => false,
        ]);

        Livewire::test('dashboard')
            ->assertSet('totalQuizzesCompleted', 1);
    }

    public function test_dashboard_tracks_monthly_user_registrations()
    {
        $this->actingAs($this->admin);

        // Create users in different months
        User::factory()->create([
            'isAdmin' => false,
            'created_at' => now()->setMonth(1),
        ]);

        User::factory()->create([
            'isAdmin' => false,
            'created_at' => now()->setMonth(1),
        ]);

        User::factory()->create([
            'isAdmin' => false,
            'created_at' => now()->setMonth(3),
        ]);

        $component = Livewire::test('dashboard');

        // Verify monthly data structure exists
        $this->assertIsArray($component->get('currentUsersPerMonth'));
        $this->assertArrayHasKey(1, $component->get('currentUsersPerMonth'));
    }

    public function test_non_admin_cannot_access_dashboard()
    {
        $user = User::factory()->create(['isAdmin' => false]);

        $this->actingAs($user);

        $response = $this->get('/dashboard');
        $response->assertStatus(403);
    }

    public function test_dashboard_initializes_all_stats()
    {
        $this->actingAs($this->admin);

        $component = Livewire::test('dashboard');

        $this->assertNotNull($component->get('currentUsers'));
        $this->assertNotNull($component->get('totalDecks'));
        $this->assertNotNull($component->get('totalCards'));
        $this->assertNotNull($component->get('totalQuizzesStarted'));
        $this->assertNotNull($component->get('totalQuizzesCompleted'));
        $this->assertNotNull($component->get('currentUsersPerMonth'));
    }
}
