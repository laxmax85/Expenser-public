<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->word(),
            'budget' => $this->faker->randomFloat(2, 10, 500), 
            'description' => $this->faker->sentence(),
            'icon' => !empty($icons) ? $icons[array_rand($icons)] : 'food-20',
        ];
    }
}
