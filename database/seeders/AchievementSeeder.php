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
            'achievement_description' => 'Complete your first lesson.',
            'achievement_requirements' => 'Complete 1 lesson'
        ]);

        Achievement::create([
            'achievement_title' => 'Quiz Master',
            'achievement_description' => 'Achieve a perfect score on a quiz.',
            'achievement_requirements' => 'Score 100% on any quiz'
        ]);

        Achievement::create([
            'achievement_title' => 'Level Up',
            'achievement_description' => 'Reach level 5.',
            'achievement_requirements' => 'Achieve level 5'
        ]);

        Achievement::create([
            'achievement_title' => 'Language Enthusiast',
            'achievement_description' => 'Complete 10 lessons.',
            'achievement_requirements' => 'Complete 10 lessons'
        ]);

        Achievement::create([
            'achievement_title' => 'Quiz Conqueror',
            'achievement_description' => 'Score 80% or higher on 5 quizzes.',
            'achievement_requirements' => 'Achieve 80% or higher on 5 quizzes'
        ]);

        Achievement::create([
            'achievement_title' => 'Language Prodigy',
            'achievement_description' => 'Reach level 10.',
            'achievement_requirements' => 'Achieve level 10'
        ]);
    }
}
