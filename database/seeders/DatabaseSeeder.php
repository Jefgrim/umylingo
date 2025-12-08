<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'firstname' => env('SEEDER_ADMIN1_FIRSTNAME'),
            'lastname' => env('SEEDER_ADMIN1_LASTNAME'),
            'username' => env('SEEDER_ADMIN1_USERNAME'),
            'password' => env('SEEDER_ADMIN1_PASSWORD'),
            'isAdmin' => true,
            'email' => env('SEEDER_ADMIN1_EMAIL'),
        ]);

        User::create([
            'firstname' => env('SEEDER_ADMIN2_FIRSTNAME'),
            'lastname' => env('SEEDER_ADMIN2_LASTNAME'),
            'username' => env('SEEDER_ADMIN2_USERNAME'),
            'password' => env('SEEDER_ADMIN2_PASSWORD'),
            'isAdmin' => true,
            'email' => env('SEEDER_ADMIN2_EMAIL'),
        ]);

        User::create([
            'firstname' => 'test',
            'lastname' => 'test',
            'username' => 'test',
            'password' => env('SEEDER_USER_PASSWORD'),
            'isAdmin' => false,
            'email' => env('SEEDER_TEST_EMAIL'),
        ]);

        User::create([
            'firstname' => 'test2',
            'lastname' => 'test2',
            'username' => 'test2',
            'password' => env('SEEDER_USER_PASSWORD'),
            'isAdmin' => false,
            'email' => env('SEEDER_TEST2_EMAIL'),
        ]);

        $this->call(DeckSeeder::class);
        $this->call(AchievementSeeder::class);
    }
}
