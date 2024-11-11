<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz>
 */
class QuizFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'card_id' => fake()->randomNumber(1),
            'user_id' => fake()->randomNumber(1),
            'answer' => 'ichi',
            'isAnswered' => fake()->boolean(),
            'isCorrect' => fake()->boolean()
        ];
    }
}
