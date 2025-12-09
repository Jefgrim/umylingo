<?php

namespace Database\Factories;

use App\Models\Card;
use App\Models\Deck;
use Illuminate\Database\Eloquent\Factories\Factory;

class CardFactory extends Factory
{
    protected $model = Card::class;

    public function definition()
    {
        return [
            'deck_id' => Deck::factory(),
            'content' => $this->faker->word(),
            'question' => $this->faker->sentence() . '?',
        ];
    }
}
