<?php

use Livewire\Volt\Component;
use App\Models\Expense;
use App\Models\RecurringExpense;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public $name;
    public $expense = null;
    public $categoryId;
    public $deleteAllExpenses = false;
    public $deleteRecurringExpense = false;

    public function mount($expense = null): void
    {
        if ($expense != null) {
            if ($expense->category->user_id !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

            $this->expense = $expense;
            $this->name = $expense->name;
        }
    }

    public function deleteExpense(): void
    {
        if ($this->deleteAllExpenses) {
            Expense::where('recurring_expense_id', $this->expense->recurring_expense_id)->delete();
        } elseif ($this->deleteRecurringExpense) {
            RecurringExpense::where('id', $this->expense->recurring_expense_id)->delete();
        }

        $this->categoryId = $this->expense->category_id;
        $this->expense->delete();

        $this->redirect('/categories/' . $this->categoryId);
    }
}; ?>

<form wire:submit.prevent="deleteExpense">
    @csrf
    <div class="grid grid-col-2 items-center gap-1 mt-3">
        @if ($expense->recurring_expense_id !== null)
            <label for="deleteAllExpenses" class="inline-flex items-center">
                <input wire:model="deleteAllExpenses" id="deleteAllExpenses" type="checkbox"
                    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                    name="remember">
                <span
                    class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Delete all expenses in series') }}</span>
            </label>

            <label for="deleteRecurringExpense" class="inline-flex items-center">
                <input wire:model="deleteRecurringExpense" id="deleteRecurringExpense" type="checkbox"
                    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                    name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Delete recurring expense') }}</span>
            </label>
        @endif
        <x-input-label class="text-gray-200 left-center mt-2 text-center">Are you sure you want to delete this
            Expense?</x-input-label>
    </div>

    <div class="flex items-center justify-between mt-7 gap-5">
        <button x-data x-on:click="$dispatch('close-modal')" type="button"
            class="text-gray-500 hover:text-gray-700 bg-transparent">Cancel</button>
        <x-delete-button>Delete Expense</x-delete-button>
    </div>
</form>
