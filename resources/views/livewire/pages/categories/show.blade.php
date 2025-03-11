<?php

use Livewire\Volt\Component;
use App\Models\Category;
use App\Models\Expense;

new class extends Component {
    public Category $category;
    public $data;

    public $nonRecurringExpenses;
    public $recurringExpenses;
    public $currentMonth;
    public $currentYear;
    public $startOfMonth;

    public function mount(Category $category, $data): void
    {
        $this->category = $category;
        $this->data = $data;

        $this->nonRecurringExpenses = $category->expenses;

        $this->recurringExpenses = $category->recurringExpenses->sortBy('recurrence_frequency');

        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
        $this->startOfMonth = now()->startOfMonth();
    }
}; ?>

<x-app-layout>

    <section>
        <x-default.modal name="editCategory" title="Edit Category" :category="$category">
            @livewire('components.modals.category-form', ['category' => $category], key($category->id))
        </x-default.modal>
    </section>

    <section>
        <x-default.modal name="deleteCategory" title="Delete Category" :category="$category">
            @livewire('components.modals.category-delete', ['category' => $category], key($category->id))
        </x-default.modal>
    </section>


    @livewire('components.expense-speed-dial', ['categoryId' => $category->id])
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 text-black dark:text-white shadow sm:rounded-lg">
                <div class="flex w-full gap-5">
                    <div>
                        <div
                            class="w-20 h-20 rounded-xl bg-gray-100 dark:bg-gray-700 pt-1 px-1 text-gray-800 dark:text-gray-400 ">
                            {{ svg('fluentui-' . $category->icon) }}
                        </div>
                    </div>

                    <div class="w-full">
                        <div class="items-center flex justify-between">
                            <div class="flex items-center gap-3">
                                <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                                    {{ $category->name }}
                                </h2>

                                <div>
                                    <div class="flex">
                                        <button x-on:click="$dispatch('open-modal', {name: 'editCategory'})">
                                            <svg class="w-6 h-6 text-gray-800 dark:text-white dark:hover:text-gray-300 hover:text-gray-600"
                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                                height="24" fill="currentColor" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd"
                                                    d="M11.32 6.176H5c-1.105 0-2 .949-2 2.118v10.588C3 20.052 3.895 21 5 21h11c1.105 0 2-.948 2-2.118v-7.75l-3.914 4.144A2.46 2.46 0 0 1 12.81 16l-2.681.568c-1.75.37-3.292-1.263-2.942-3.115l.536-2.839c.097-.512.335-.983.684-1.352l2.914-3.086Z"
                                                    clip-rule="evenodd" />
                                                <path fill-rule="evenodd"
                                                    d="M19.846 4.318a2.148 2.148 0 0 0-.437-.692 2.014 2.014 0 0 0-.654-.463 1.92 1.92 0 0 0-1.544 0 2.014 2.014 0 0 0-.654.463l-.546.578 2.852 3.02.546-.579a2.14 2.14 0 0 0 .437-.692 2.244 2.244 0 0 0 0-1.635ZM17.45 8.721 14.597 5.7 9.82 10.76a.54.54 0 0 0-.137.27l-.536 2.84c-.07.37.239.696.588.622l2.682-.567a.492.492 0 0 0 .255-.145l4.778-5.06Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>

                                        <button x-on:click="$dispatch('open-modal', {name: 'deleteCategory'})">
                                            <svg class="w-6 h-6 text-gray-800 dark:text-white hover:text-red-500 dark:hover:text-red-500"
                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                                height="24" fill="currentColor" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd"
                                                    d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <p class="text-2xl"><strong>Budget:</strong> ${{ number_format($category->budget, 2) }}</p>
                        </div>

                        <hr class="my-4 h-0.5 border-t-0 bg-neutral-100 dark:bg-white/10" />

                        <p>
                            {{ $category->description }}
                        </p>
                    </div>
                </div>
            </div>

            @if ($nonRecurringExpenses->isNotEmpty())
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 text-black dark:text-white shadow sm:rounded-lg">
                    @livewire('components.charts.budget-spending-chart', ['id' => 1, 'data' => $data], key(1))
                </div>
            @else
                 <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 text-black dark:text-white shadow sm:rounded-lg gap-5">
                    <div>
                        <div class="justify-center text-center w-24 mx-auto">
                            <x-fluentui-database-warning-20 class="text-gray-800 dark:text-gray-400 m-1" />
                        </div>
                        <h3 class="text-xl text-center">No data available.</h3>
                        <div class="text-center text-gray-500 dark:text-gray-400 mt-2">
                            <p>Adding a expense or a subscriptions will diplay data.</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 text-black dark:text-white shadow sm:rounded-lg gap-5">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    Subscriptions / Recurring expenses
                </h2>

                <div class="mt-4">
                    @if ($recurringExpenses->isEmpty())
                        <div>
                            <div>
                                <div class="justify-center text-center w-24 mx-auto">
                                    <x-fluentui-video-clip-off-20 class="text-gray-800 dark:text-gray-400 m-1" />
                                </div>
                                <h3 class="text-xl text-center">No subscriptions / recurring expenses yet.</h3>
                                <div class="text-center text-gray-500 dark:text-gray-400 mt-2">
                                    <p>Empty recurring expenses? Sounds like a great time to subscribe to something
                                        unnecessary 🙈</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="space-y-10">
                            @foreach ($recurringExpenses->groupBy('recurrence_frequency') as $frequency => $recurringExpenses)
                                <h2 class="text-2xl font-bold text-center">{{ ucfirst($frequency) }} Recurring Expenses
                                </h2>
                                @foreach ($recurringExpenses as $recurringExpense)
                                    <x-recurring-expense :recurringExpense="$recurringExpense">
                                        <div x-data="{
                                            amount: {{ $recurringExpense->amount }},
                                            charged_at_date: new Date('{{ \Carbon\Carbon::parse($recurringExpense->expense_date)->toDateString() }}'), // Convert to JavaScript Date
                                            recurrence_frequency: '{{ $recurringExpense->recurrence_frequency }}',
                                            recurrence_interval: {{ $recurringExpense->recurrence_interval }},
                                            spending: 0,
                                            chargeThisMonth: 0,
                                            init() {
                                                // Calculate the spending for the current month
                                                this.spending = parseFloat(calculateSpendingForCurrentMonth({
                                                    amount: this.amount,
                                                    start_date: this.charged_at_date,
                                                    recurrence_frequency: this.recurrence_frequency,
                                                    recurrence_interval: this.recurrence_interval
                                                }));
                                        
                                                // Check if the expense should be charged this month
                                                const shouldCharge = isExpenseChargedThisMonth({
                                                    amount: this.amount,
                                                    start_date: this.charged_at_date,
                                                    recurrence_frequency: this.recurrence_frequency,
                                                    recurrence_interval: this.recurrence_interval
                                                });
                                        
                                                if (shouldCharge === true) {
                                                    this.chargeThisMonth = parseFloat(this.amount);
                                                } else {
                                                    this.chargeThisMonth = 0;
                                                }
                                            }
                                        }">
                                            <div class="flex gap-2">
                                                <p>Amount: </p>
                                                <p>€{{ $recurringExpense->amount }}</p>
                                            </div>

                                            <div class="flex gap-2">
                                                <p>Date: </p>
                                                <p>{{ $recurringExpense->expense_date }}</p>
                                            </div>

                                            <div class="flex gap-2">
                                                <p>Interval: </p>
                                                <p>{{ $recurringExpense->recurrence_interval }}</p>
                                            </div>

                                            <div class="flex gap-2">
                                                <p>Spending for the Current Month: </p>
                                                <!-- Display the calculated spending using Alpine.js -->
                                                <p>€<span x-text="spending.toFixed(2)"></span></p>
                                            </div>

                                            <!-- New field to display charge amount based on whether it should be charged this month -->
                                            <div class="flex gap-2">
                                                <p>Charge this Month: </p>
                                                <p>€<span x-text="chargeThisMonth.toFixed(2)"></span></p>
                                            </div>

                                        </div>
                                    </x-recurring-expense>
                                @endforeach
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 text-black dark:text-white shadow sm:rounded-lg gap-5">

                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    Single Expenses
                </h2>

                @livewire('components.expense-list', ['categoryId' => $category->id], key($category->id))
            </div>
        </div>
    </div>
</x-app-layout>
