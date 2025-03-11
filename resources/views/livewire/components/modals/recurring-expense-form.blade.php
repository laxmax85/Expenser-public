<?php

use Livewire\Volt\Component;
use App\Models\RecurringExpense;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

new class extends Component
{
    public $categoryId = -1;
    public $buttonText;
    public $name;
    public $description;
    public $amount;
    public $expense_date;
    public $recurrence_frequency;
    public $recurrence_interval;
    public $expenseId = null;

    public function mount($categoryId, $recurringExpense = null)
    {
        $this->categoryId = $categoryId;

        if ($recurringExpense) {
            $this->expenseId = $recurringExpense->id;
            $this->buttonText = 'Update Recurring expense';
            $this->name = $recurringExpense->name;
            $this->description = $recurringExpense->description;
            $this->amount = $recurringExpense->amount;
            $this->expense_date = $recurringExpense->expense_date;
            $this->recurrence_frequency = $recurringExpense->recurrence_frequency;
            $this->recurrence_interval = $recurringExpense->recurrence_interval;
        } else {
            $this->buttonText = 'Create Recurring expense';
            $this->name = '';
            $this->description = '';
            $this->amount = '';
            $this->expense_date = '';
            $this->recurrence_frequency = 'monthly';
            $this->recurrence_interval = 1; 
        }
    }

    public function store()
    {
        $expenseAttributes = $this->validate([
            'name' => ['required', 'max:255'],
            'description' => ['nullable', 'max:1000'],
            'amount' => ['required'],
            'expense_date' => ['required', 'date'],
            'recurrence_frequency' => ['required', 'in:monthly,yearly,none'],
            'recurrence_interval' => ['required', 'integer'],
        ]);

        $expenseAttributes['user_id'] = Auth::id();
        $expenseAttributes['category_id'] = $this->categoryId;

        if ($this->expenseId) {
            $recurringExpense = RecurringExpense::findOrFail($this->expenseId);
            if ($recurringExpense->category->user_id !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }
            $recurringExpense->update($expenseAttributes);
        } else {
            $recurringExpense = RecurringExpense::create($expenseAttributes);
        }

        $this->createNormalExpenses($recurringExpense);

        $this->redirect("/categories/{$this->categoryId}");
    }

    public function createNormalExpenses($recurringExpense)
    {
        $startDate = Carbon::parse($recurringExpense->expense_date);
        $endDate = Carbon::now();
        $interval = (int) $recurringExpense->recurrence_interval;
        $frequency = $recurringExpense->recurrence_frequency;

        if($frequency == 'monthly') {
            $endDate = $endDate->endOfMonth();
        }
        else if($frequency == 'yearly') {
            $endDate = $endDate->endOfYear();
        }

        while ($startDate->lessThanOrEqualTo($endDate)) {
            Expense::create([
                'name' => $recurringExpense->name,
                'description' => $recurringExpense->description,
                'amount' => $recurringExpense->amount,
                'expense_date' => $startDate->toDateString(),
                'category_id' => $recurringExpense->category_id,
                'user_id' => Auth::id(),
                'recurring_expense_id' => $recurringExpense->id,
            ]);

            if ($frequency == 'monthly') {
                $startDate->addMonths($interval);
            } elseif ($frequency == 'yearly') {
                $startDate->addYears($interval);
            }
        }
    }
}; ?>

<form wire:submit.prevent="store">
    @csrf
    <div class="grid grid-col-2 items-center gap-1 mt-3">
        <x-input-label class="text-gray-200 left-center">Expense Name</x-input-label>
        <x-text-input id="name" wire:model="name" class="text-gray-400" placeholder="Enter expense name" value="{{ $name }}"/>

        <x-input-label class="text-gray-200 left-center mt-2">Description</x-input-label>
        <x-text-input id="description" wire:model="description" class="text-gray-400" placeholder="Enter description" value="{{ $description }}"/>

        <x-input-label class="text-gray-200 left-center mt-2">Amount</x-input-label>
        <x-text-input id="amount" wire:model="amount" type="number" min=0 step=0.01 class="text-gray-400" placeholder="Enter amount" value="{{ $amount }}"/>

        <x-input-label class="text-gray-200 left-center mt-2">Expense Date</x-input-label>
        <x-text-input id="expense_date" wire:model="expense_date" type="date" class="text-gray-400" value="{{ $expense_date }}"/>

        <x-input-label class="text-gray-200 left-center mt-2">Recurring Frequency</x-input-label>
        <select id="recurrence_frequency" wire:model="recurrence_frequency" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
            <option value="monthly">Monthly</option>
            <option value="yearly">Yearly</option>
        </select>

        <x-input-label class="text-gray-200 left-center mt-2">Recurring Interval</x-input-label>
        <x-text-input id="recurrence_interval" wire:model="recurrence_interval" type="number" min=0 step=1 class="text-gray-400" value="{{ $recurrence_interval }}"/>
    </div>

    <div class="flex items-center justify-between mt-7 gap-5">
        <button x-data x-on:click="$dispatch('close-modal')" type="button" class="text-gray-500 hover:text-gray-700 bg-transparent">Cancel</button>
        <x-primary-button>{{ $buttonText }}</x-primary-button>
    </div>
</form>
