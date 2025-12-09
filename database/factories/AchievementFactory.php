<?php

namespace Database\Factories;

use App\Models\Achievement;
use Illuminate\Database\Eloquent\Factories\Factory;

class AchievementFactory extends Factory
{
    protected $model = Achievement::class;

    public function definition()
    {
        return [
            'achievement_title' => $this->faker->sentence(3),
            'achievement_description' => $this->faker->sentence(8),
            'achievement_requirements' => $this->faker->sentence(5),
        ];
    }
}
