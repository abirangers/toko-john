<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::with('roles')->whereHas('roles', function ($query) {
            $query->where('name', 'user');
        })->inRandomOrder()->first();

        return [
            'user_id' => $user ? $user->id : User::factory(),
            'total_price' => fake()->randomFloat(2, 1, 100),
            'status' => fake()->randomElement(['pending', 'paid', 'cancelled']),
            'created_at' => fake()->dateTimeBetween('-24 months', 'now'),
        ];
    }
}