<?php

use Livewire\Volt\Component;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public $categoryId = -1;
    public $buttonText;
    public $name;
    public $description;
    public $amount;
    public $expense_date;
    public $expenseId = null;

    public function mount($categoryId, $expense = null)
    {
        $this->categoryId = $categoryId;

        if ($expense) {
            $this->expenseId = $expense->id;
            $this->buttonText = 'Update Expense';
            $this->name = $expense->name;
            $this->description = $expense->description;
            $this->amount = $expense->amount;
            $this->expense_date = $expense->expense_date;
        } else {
            $this->buttonText = 'Create Expense';
            $this->name = '';
            $this->description = '';
            $this->amount = '';
            $this->expense_date = '';
        }
    }

    public function store()
    {
        $expenseAttributes = $this->validate([
            'name' => ['required', 'max:255'],
            'description' => ['nullable', 'max:1000'],
            'amount' => ['required'],
            'expense_date' => ['required', 'date'],
        ]);

        $expenseAttributes['user_id'] = Auth::id();
        $expenseAttributes['category_id'] = $this->categoryId;

        if ($this->expenseId) {
            $expense = Expense::findOrFail($this->expenseId);
            if ($expense->category->user_id !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }
            $expense($expenseAttributes);
        } else {
            $expense = Expense::create($expenseAttributes);
        }

        $this->redirect("/categories/{$this->categoryId}");
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
    </div>

    <div class="flex items-center justify-between mt-7 gap-5">
        <button x-data x-on:click="$dispatch('close-modal')" type="button" class="text-gray-500 hover:text-gray-700 bg-transparent">Cancel</button>
        <x-primary-button>{{ $buttonText }}</x-primary-button>
    </div>
</form>
