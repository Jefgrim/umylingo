<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'firstname' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'username' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password1234567890'), // default password with 10+ chars
            'remember_token' => Str::random(10),
            'isAdmin' => false,
        ];
    }

    public function admin()
    {
        return $this->state([
            'isAdmin' => true,
        ]);
    }
}
