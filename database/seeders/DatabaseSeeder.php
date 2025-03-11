<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Expense;
use App\Models\RecurringExpense;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */

    private $user;

    public function run(): void
    {
        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.test',
            'password' => 'TESTTEST'
        ]);

        $icons = collect(config('icons.icons'))->flatten()->toArray();

        Category::factory(10)->create()->each(function ($category) use ($icons) {
            $category->user_id = $this->user->id;
            $category->save();

            $recurringExpenses = RecurringExpense::factory(5)->create();

            foreach ($recurringExpenses as $recurringExpense) {
                $recurringExpense->category_id = $category->id;
                $recurringExpense->save();

                $startDate = Carbon::parse($recurringExpense->expense_date);
                $today = Carbon::now()->endOfMonth();

                if ($recurringExpense->recurrence_frequency === 'monthly') {
                    $monthsDiff = $startDate->diffInMonths($today);
                    for ($i = 0; $i <= $monthsDiff; $i++) {
                        $expenseDate = $startDate->copy()->addMonths($i);

                        if ($expenseDate->lte($today)) {
                            Expense::create([
                                'category_id' => $category->id,
                                'name' => $recurringExpense->name,
                                'description' => $recurringExpense->description,
                                'amount' => $recurringExpense->amount,
                                'expense_date' => $expenseDate->format('Y-m-d'),
                                'recurring_expense_id' => $recurringExpense->id,
                            ]);
                        }
                    }
                } elseif ($recurringExpense->recurrence_frequency === 'yearly') {
                    $yearsDiff = $startDate->diffInYears($today);
                    for ($i = 0; $i <= $yearsDiff; $i++) {
                        $expenseDate = $startDate->copy()->addYears($i);

                        if ($expenseDate->lte($today)) {
                            Expense::create([
                                'category_id' => $category->id,
                                'name' => $recurringExpense->name,
                                'description' => $recurringExpense->description,
                                'amount' => $recurringExpense->amount,
                                'expense_date' => $expenseDate->format('Y-m-d'),
                                'recurring_expense_id' => $recurringExpense->id,
                            ]);
                        }
                    }
                }
            }

            $nonRecurringExpensesCount = rand(10, 20);
            Expense::factory($nonRecurringExpensesCount)->create([
                'category_id' => $category->id,
                'recurring_expense_id' => null,
            ]);
        });
    }
}
