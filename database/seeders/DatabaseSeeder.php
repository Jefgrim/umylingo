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

        $admin1 = config('seeder.admin1');
        $admin2 = config('seeder.admin2');
        $users = config('seeder.users', []);

        User::create([
            'firstname' => $admin1['firstname'],
            'lastname' => $admin1['lastname'],
            'username' => $admin1['username'],
            'password' => $admin1['password'],
            'isAdmin' => true,
            'email' => $admin1['email'],
        ]);

        User::create([
            'firstname' => $admin2['firstname'],
            'lastname' => $admin2['lastname'],
            'username' => $admin2['username'],
            'password' => $admin2['password'],
            'isAdmin' => true,
            'email' => $admin2['email'],
        ]);

        foreach ($users as $user) {
            User::create([
                'firstname' => $user['firstname'],
                'lastname' => $user['lastname'],
                'username' => $user['username'],
                'password' => $user['password'],
                'isAdmin' => false,
                'email' => $user['email'],
            ]);
        }

        $this->call(DeckSeeder::class);
        $this->call(AchievementSeeder::class);
    }
}
