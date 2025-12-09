<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Deck;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DeckCrudTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create([
            'username' => 'admin',
            'isAdmin' => true,
        ]);
    }

    public function test_admin_can_access_create_deck_page()
    {
        $this->actingAs($this->admin);

        Livewire::test('create-deck')
            ->assertStatus(200);
    }

    public function test_admin_can_create_deck_with_cards()
    {
        $this->actingAs($this->admin);

        Livewire::test('create-deck')
            ->set('form.language', 'Spanish')
            ->set('form.deck_description', 'Learn basic Spanish vocabulary')
            ->set('cardCount', 2)
            ->set('cards', [
                [
                    'content' => 'Hola',
                    'question' => 'What does "Hola" mean?',
                    'choices' => [
                        ['choice' => 'Hello', 'isCorrect' => true],
                        ['choice' => 'Goodbye', 'isCorrect' => false],
                        ['choice' => 'Thank you', 'isCorrect' => false],
                        ['choice' => 'Please', 'isCorrect' => false],
                    ],
                ],
                [
                    'content' => 'Adiós',
                    'question' => 'What does "Adiós" mean?',
                    'choices' => [
                        ['choice' => 'Hello', 'isCorrect' => false],
                        ['choice' => 'Goodbye', 'isCorrect' => true],
                        ['choice' => 'Thank you', 'isCorrect' => false],
                        ['choice' => 'Please', 'isCorrect' => false],
                    ],
                ],
            ])
            ->call('store')
            ->assertRedirect('/dashboard/decks');

        $this->assertDatabaseHas('decks', [
            'language' => 'Spanish',
            'deck_description' => 'Learn basic Spanish vocabulary',
        ]);

        $this->assertDatabaseHas('cards', [
            'content' => 'Hola',
            'question' => 'What does "Hola" mean?',
        ]);
    }

    public function test_admin_can_update_deck()
    {
        $this->actingAs($this->admin);

        $deck = Deck::factory()->create([
            'language' => 'French',
            'deck_description' => 'Old description',
        ]);

        // Need to load the deck with cards for the form to work properly
        $deck->cards()->create([
            'content' => 'Bonjour',
            'question' => 'What does Bonjour mean?',
        ]);

        $response = Livewire::test('edit-deck', ['deck' => $deck])
            ->set('form.language', 'French Updated')
            ->set('form.deck_description', 'Updated description')
            ->call('update');

        // Check if redirect happened or if validation passed
        $this->assertDatabaseHas('decks', [
            'id' => $deck->id,
            'language' => 'French Updated',
            'deck_description' => 'Updated description',
        ]);
    }

    public function test_admin_can_archive_deck()
    {
        $this->actingAs($this->admin);

        $deck = Deck::factory()->create([
            'language' => 'German',
            'isArchived' => null,
        ]);

        Livewire::test('edit-deck', ['deck' => $deck])
            ->call('toggleArchive')
            ->assertRedirect('/dashboard/decks');

        $deck->refresh();
        $this->assertNotNull($deck->isArchived);
    }

    public function test_admin_can_add_card_to_existing_deck()
    {
        $this->actingAs($this->admin);

        $deck = Deck::factory()->create();

        Livewire::test('edit-deck', ['deck' => $deck])
            ->call('addCard')
            ->assertSet('cardCount', 1);
    }

    public function test_regular_user_cannot_access_create_deck()
    {
        $user = User::factory()->create([
            'isAdmin' => false,
        ]);

        $this->actingAs($user);

        $response = $this->get('/deck/create');
        
        // Should return 404 or 403 depending on middleware/route protection
        $this->assertTrue(in_array($response->status(), [403, 404]));
    }

    public function test_guest_cannot_access_deck_management()
    {
        $response = $this->get('/dashboard/decks');
        
        $response->assertRedirect('/login');
    }
}
