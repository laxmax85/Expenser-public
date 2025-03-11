<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RecurringExpense>
 */
class RecurringExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'amount' => $this->faker->randomFloat(2, 5, 100),
            'expense_date' => $this->faker->dateTimeBetween('-7 months', 'now')->format('Y-m-d'),
            'recurrence_frequency' => $this->faker->randomElement(['monthly', 'yearly']),
            'recurrence_interval' => $this->faker->numberBetween(1, 12),
        ];
    }
}
