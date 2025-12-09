<?php

namespace Database\Factories;

use App\Models\Deck;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeckFactory extends Factory
{
    protected $model = Deck::class;

    public function definition()
    {
        $languages = ['Spanish', 'French', 'German', 'Italian', 'Japanese', 'Korean', 'Mandarin', 'Portuguese'];
        
        return [
            'language' => $this->faker->randomElement($languages),
            'deck_description' => $this->faker->sentence(10),
            'isArchived' => null,
        ];
    }

    public function archived()
    {
        return $this->state([
            'isArchived' => now(),
        ]);
    }
}
