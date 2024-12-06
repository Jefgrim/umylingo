<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Achievement::create([
            'achievement_title' => 'First Steps',
            'achievement_description' => 'You have learned your first deck.',
            'achievement_requirements' => 'Learn your first deck'
        ]);

        Achievement::create([
            'achievement_title' => 'Quiz Master',
            'achievement_description' => 'You Achieved a perfect score on a quiz.',
            'achievement_requirements' => 'Score 100% on any quiz'
        ]);

        Achievement::create([
            'achievement_title' => 'Level Up',
            'achievement_description' => 'You reached level 5.',
            'achievement_requirements' => 'Achieve level 5'
        ]);

        Achievement::create([
            'achievement_title' => 'Language Enthusiast',
            'achievement_description' => 'You have learned 10 decks.',
            'achievement_requirements' => 'Learn 10 decks'
        ]);

        Achievement::create([
            'achievement_title' => 'Quiz Conqueror',
            'achievement_description' => 'You have scored 80% or higher on 5 quizzes.',
            'achievement_requirements' => 'Achieve 80% or higher on 5 quizzes'
        ]);

        Achievement::create([
            'achievement_title' => 'Language Prodigy',
            'achievement_description' => 'You have reached level 10.',
            'achievement_requirements' => 'Achieve level 10'
        ]);
    }
}
