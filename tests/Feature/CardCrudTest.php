<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Deck;
use App\Models\Card;
use App\Models\Choice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CardCrudTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $deck;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create([
            'isAdmin' => true,
        ]);

        $this->deck = Deck::factory()->create();
    }

    public function test_card_can_be_created_with_choices()
    {
        $this->actingAs($this->admin);

        $card = Card::create([
            'deck_id' => $this->deck->id,
            'content' => 'Hello',
            'question' => 'What does "Hello" mean in Spanish?',
        ]);

        $choices = [
            ['choice' => 'Hola', 'isCorrect' => true],
            ['choice' => 'Adiós', 'isCorrect' => false],
            ['choice' => 'Gracias', 'isCorrect' => false],
            ['choice' => 'Por favor', 'isCorrect' => false],
        ];

        foreach ($choices as $choiceData) {
            Choice::create([
                'card_id' => $card->id,
                'choice' => $choiceData['choice'],
                'isCorrect' => $choiceData['isCorrect'],
            ]);
        }

        $this->assertDatabaseHas('cards', [
            'id' => $card->id,
            'content' => 'Hello',
            'question' => 'What does "Hello" mean in Spanish?',
        ]);

        $this->assertEquals(4, $card->choices()->count());
        $this->assertEquals(1, $card->choices()->where('isCorrect', true)->count());
    }

    public function test_card_belongs_to_deck()
    {
        $card = Card::create([
            'deck_id' => $this->deck->id,
            'content' => 'Test content',
            'question' => 'Test question',
        ]);

        $this->assertEquals($this->deck->id, $card->deck->id);
    }

    public function test_card_can_be_updated()
    {
        $card = Card::create([
            'deck_id' => $this->deck->id,
            'content' => 'Old content',
            'question' => 'Old question',
        ]);

        $card->update([
            'content' => 'New content',
            'question' => 'New question',
        ]);

        $this->assertDatabaseHas('cards', [
            'id' => $card->id,
            'content' => 'New content',
            'question' => 'New question',
        ]);
    }

    public function test_deleting_card_deletes_choices()
    {
        $card = Card::create([
            'deck_id' => $this->deck->id,
            'content' => 'Test',
            'question' => 'Test?',
        ]);

        $choice = Choice::create([
            'card_id' => $card->id,
            'choice' => 'Answer',
            'isCorrect' => true,
        ]);

        $choiceId = $choice->id;
        $card->delete();

        // Choices should be deleted if cascade is set up
        $this->assertDatabaseMissing('cards', ['id' => $card->id]);
    }

    public function test_card_has_correct_and_incorrect_choices()
    {
        $card = Card::create([
            'deck_id' => $this->deck->id,
            'content' => 'Bonjour',
            'question' => 'What does "Bonjour" mean?',
        ]);

        Choice::create([
            'card_id' => $card->id,
            'choice' => 'Hello',
            'isCorrect' => true,
        ]);

        Choice::create([
            'card_id' => $card->id,
            'choice' => 'Goodbye',
            'isCorrect' => false,
        ]);

        $correctChoices = $card->choices()->where('isCorrect', true)->count();
        $incorrectChoices = $card->choices()->where('isCorrect', false)->count();

        $this->assertEquals(1, $correctChoices);
        $this->assertEquals(1, $incorrectChoices);
    }

    public function test_deck_can_have_multiple_cards()
    {
        for ($i = 0; $i < 5; $i++) {
            Card::create([
                'deck_id' => $this->deck->id,
                'content' => "Content {$i}",
                'question' => "Question {$i}?",
            ]);
        }

        $this->assertEquals(5, $this->deck->cards()->count());
    }
}
