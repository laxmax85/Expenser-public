<?php

use Livewire\Volt\Component;
use App\Models\Expense;
use App\Models\RecurringExpense;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public $name;
    public $recurringExpense = null;
    public $deleteAllExpenses = false;

    public function mount($recurringExpense = null): void
    {
        if ($recurringExpense != null) {
            
            if ($recurringExpense->category->user_id !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

            $this->recurringExpense = $recurringExpense;
            $this->name = $recurringExpense->name;
        }
    }

    public function delete(): void
    {
        if ($this->deleteAllExpenses) {
            Expense::where('recurring_expense_id', $this->recurringExpense->id)->delete();
        } else {
            Expense::where('recurring_expense_id', $this->recurringExpense->id)->update(['recurring_expense_id' => null]);
        }

        $this->recurringExpense->delete();

        $this->redirect('/categories/' . $this->recurringExpense->category_id);
    }
}; ?>

<form wire:submit.prevent="delete">
    @csrf
    <div class="grid grid-col-2 items-center gap-1 mt-3">
        <label for="deleteAllExpenses" class="inline-flex items-center">
            <input wire:model="deleteAllExpenses" id="deleteAllExpenses" type="checkbox"
                class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                name="remember">
            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Delete all expenses in series') }}</span>
        </label>
        <x-input-label class="text-gray-200 left-center mt-2 text-center">Are you sure you want to delete this
            Expense?</x-input-label>
    </div>

    <div class="flex items-center justify-between mt-7 gap-5">
        <button x-data x-on:click="$dispatch('close-modal')" type="button"
            class="text-gray-500 hover:text-gray-700 bg-transparent">Cancel</button>
        <x-delete-button>Delete recurring expense</x-delete-button>
    </div>
</form>
