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

        User::factory()->create([
            'firstname' => 'Jefgrim',
            'lastname' => 'Alvar',
            'username' => 'jefgrim',
            'password' => 'Alex0807',
            'isAdmin' => true,
            'email' => 'jefgrim@example.com',
        ]);
        
        User::factory()->create([
            'firstname' => 'test',
            'lastname' => 'test',
            'username' => 'test',
            'password' => 'Alex0807',
            'isAdmin' => false,
            'email' => 'test@example.com',
        ]);

        User::factory()->create([
            'firstname' => 'test2',
            'lastname' => 'test2',
            'username' => 'test2',
            'password' => 'Alex0807',
            'isAdmin' => false,
            'email' => 'test2@example.com',
        ]);

        // $this->call(UserSeeder::class);
        // $this->call(DeckSeeder::class);
        // $this->call(CardSeeder::class);
        // $this->call(QuizSeeder::class);
        // $this->call(ChoiceSeeder::class);
    }
}
