<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Expense;

new class extends Component {
    public $loadAmount = 10;
    public $expenses;
    public $categoryId;
    public $hasMoreExpenses = true;

    public function mount($categoryId)
    {
        $this->categoryId = $categoryId;
        $this->authorizeUser();

        $this->expenses = collect(); 

        $this->loadExpenses();
    }

    public function authorizeUser()
    {
        if (Category::find($this->categoryId)->user_id !== Auth::user()->id) {
            abort(403);
        }
    }

    public function loadExpenses()
    {
        $newExpenses = Expense::where('category_id', $this->categoryId)
            ->orderBy('expense_date', 'desc')
            ->skip($this->expenses->count())
            ->take($this->loadAmount)
            ->get();

        $this->expenses = $this->expenses->merge($newExpenses)->unique('id');

        if ($newExpenses->count() < $this->loadAmount) {
            $this->hasMoreExpenses = false;
        }
    }

    public function loadMore()
    {
        $this->loadExpenses();
    }
}; ?>

<div x-data="{ selectedExpense: null }">
    @if ($expenses->isEmpty())
        <div>
            <div class="justify-center text-center w-24 mx-auto">
                <x-fluentui-money-dismiss-24 class="text-gray-800 dark:text-gray-400 m-1" />
            </div>
            <h3 class="text-xl text-center">No expenses yet.</h3>
            <div class="text-center text-gray-500 dark:text-gray-400 mt-2">
                <p>No expenses found! Time to add some new ones and watch your money fly away! 💸</p>
            </div>
        </div>
    @else
        @foreach ($expenses as $expense)
            <x-expense :expense="$expense">
                <div class="flex gap-2">
                    <p>Amount: </p>
                    <p>€{{ number_format($expense->amount, 2, ',', '.') }}</p>
                </div>

                <div class="flex gap-2">
                    <p>Date: </p>
                    <p>{{ $expense->expense_date }}</p>
                </div>

                @if ($expense->recurring_expense_id != null)
                    <div class="flex gap-2">
                        <p>Recurring expense: </p>
                        <p class="font-bold">{{ $expense->RecurringExpense->name }}</p>
                    </div>
                @endif
            </x-expense>
        @endforeach

        @if ($hasMoreExpenses)
            <div class="mt-4 flex justify-center">
                <button wire:click="loadMore"
                    class="px-4 py-2 text-blue-500 border border-blue-500 rounded-full flex items-center gap-2 hover:bg-blue-500 hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Load More
                </button>
            </div>
        @endif
    @endif
</div>
