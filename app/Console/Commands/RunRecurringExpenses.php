<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RunRecurringExpenses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-recurring-expenses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run a task every new month';

    /**
     * Execute the console command.
     */

    public function handle(): void
    {
        Log::info("RunRecurringExpenses task started executing at: " . now());

        $endOfMonth = Carbon::now()->endOfMonth();

        Category::all()->each(function ($category) use ($endOfMonth) {

            $recurringExpenses = $category->recurringExpense;
            $recurringExpenses->each(function ($recurringExpense) use ($category, $endOfMonth) {

                $startDate = Carbon::parse($recurringExpense->charged_at_date);
                if ($recurringExpense->recurrence_frequency === 'monthly') {
                    if ($startDate->month <= $endOfMonth->month && $startDate->year <= $endOfMonth->year) {
                        $expenseDate = $startDate->copy()->addMonths($this->getMonthsDiff($startDate, $endOfMonth));
                        $this->createExpense($category, $recurringExpense, $expenseDate);
                    }
                } elseif ($recurringExpense->recurrence_frequency === 'yearly') {
                    if ($startDate->year <= $endOfMonth->year) {
                        $expenseDate = $startDate->copy()->addYears($this->getYearsDiff($startDate, $endOfMonth));
                        $this->createExpense($category, $recurringExpense, $expenseDate);
                    }
                }
            });
        });

        Log::info("RunRecurringExpenses task finished executing at: " . now());
    }

    private function getMonthsDiff($startDate, $today)
    {
        return $startDate->diffInMonths($today);
    }

    private function getYearsDiff($startDate, $today)
    {
        return $startDate->diffInYears($today);
    }

    private function createExpense($category, $recurringExpense, $expenseDate)
    {
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
