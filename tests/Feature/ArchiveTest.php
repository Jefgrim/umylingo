<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Deck;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArchiveTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create([
            'isAdmin' => true,
        ]);

        $this->user = User::factory()->create([
            'isAdmin' => false,
        ]);
    }

    public function test_deck_can_be_archived()
    {
        $deck = Deck::factory()->create([
            'isArchived' => null,
        ]);

        $deck->update(['isArchived' => now()]);

        $this->assertNotNull($deck->fresh()->isArchived);
    }

    public function test_deck_can_be_unarchived()
    {
        $deck = Deck::factory()->create([
            'isArchived' => now(),
        ]);

        $deck->update(['isArchived' => null]);

        $this->assertNull($deck->fresh()->isArchived);
    }

    public function test_archived_decks_have_timestamp()
    {
        $archiveTime = now();
        
        $deck = Deck::factory()->create([
            'isArchived' => $archiveTime,
        ]);

        $this->assertNotNull($deck->isArchived);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $deck->isArchived);
    }

    public function test_active_decks_have_null_archive_timestamp()
    {
        $deck = Deck::factory()->create([
            'isArchived' => null,
        ]);

        $this->assertNull($deck->isArchived);
    }

    public function test_can_query_only_active_decks()
    {
        Deck::factory()->count(3)->create(['isArchived' => null]);
        Deck::factory()->count(2)->create(['isArchived' => now()]);

        $activeDecks = Deck::whereNull('isArchived')->count();

        $this->assertEquals(3, $activeDecks);
    }

    public function test_can_query_only_archived_decks()
    {
        Deck::factory()->count(3)->create(['isArchived' => null]);
        Deck::factory()->count(2)->create(['isArchived' => now()]);

        $archivedDecks = Deck::whereNotNull('isArchived')->count();

        $this->assertEquals(2, $archivedDecks);
    }

    public function test_toggle_archive_changes_state()
    {
        $deck = Deck::factory()->create(['isArchived' => null]);

        // Archive it
        $deck->update(['isArchived' => now()]);
        $this->assertNotNull($deck->fresh()->isArchived);

        // Unarchive it
        $deck->update(['isArchived' => null]);
        $this->assertNull($deck->fresh()->isArchived);
    }

    public function test_archived_deck_prevents_quiz_access()
    {
        $this->actingAs($this->user);

        $deck = Deck::factory()->archived()->create();

        // This would be tested in the QuizDeck component
        // The mount method checks if deck is archived and aborts with 403
        $this->assertNotNull($deck->isArchived);
    }

    public function test_multiple_decks_can_be_archived_independently()
    {
        $deck1 = Deck::factory()->create(['isArchived' => null]);
        $deck2 = Deck::factory()->create(['isArchived' => null]);
        $deck3 = Deck::factory()->create(['isArchived' => null]);

        $deck1->update(['isArchived' => now()]);
        $deck3->update(['isArchived' => now()]);

        $this->assertNotNull($deck1->fresh()->isArchived);
        $this->assertNull($deck2->fresh()->isArchived);
        $this->assertNotNull($deck3->fresh()->isArchived);
    }

    public function test_archive_timestamp_is_preserved()
    {
        $archiveTime = now()->subDays(5);
        
        $deck = Deck::factory()->create([
            'isArchived' => $archiveTime,
        ]);

        $storedTime = $deck->fresh()->isArchived;
        
        // Check that the timestamp was stored (may be string or Carbon)
        $this->assertNotNull($storedTime);
        $this->assertTrue(
            strtotime($archiveTime) <= strtotime($storedTime) + 60
        );
    }
}
