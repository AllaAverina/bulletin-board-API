<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'created_at' => fake()->randomElement([now(), fake()->dateTimeBetween('-1 year', now())]),
            'title' => ucfirst(fake()->words(rand(1, 10), true)),
            'price' => fake()->randomFloat(2, 0, 50000),
            'description' => fake()->text(),
            'user_id' => User::get()->random()->id,
        ];
    }
}
