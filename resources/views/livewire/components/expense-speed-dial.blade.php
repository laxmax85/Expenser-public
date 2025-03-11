<?php

use Livewire\Volt\Component;

new class extends Component {
    
    
    public $categoryId;

    public function mount(int $categoryId)
    {
        $this->categoryId = $categoryId;
    }

}; ?>

<div>
    <section>
        <x-default.modal name="single-expense-form" title="Create Expense">
            @livewire('components.modals.single-expense-form', ['categoryId' => $categoryId])
        </x-default.modal>
    </section>

    <section>
        <x-default.modal name="recurring-expense-form" title="Create recurring expense">
            @livewire('components.modals.recurring-expense-form', ['categoryId' => $categoryId])
        </x-default.modal>
    </section>

    <section>
        <div data-dial-init class="fixed bottom-6 end-6 group">
            <div id="speed-dial-menu-text-outside-button-square"
                class="flex flex-col items-center mb-4 space-y-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <button type="button" x-data x-on:click="$dispatch('open-modal', { name: 'recurring-expense-form'})"
                    class="relative w-[52px] h-[52px] text-gray-500 bg-white rounded-full border border-gray-200 dark:border-gray-600 hover:text-gray-900 shadow-sm dark:hover:text-white dark:text-gray-400 hover:bg-gray-50 dark:bg-gray-700 dark:hover:bg-gray-600 focus:ring-4 focus:ring-gray-300 focus:outline-none dark:focus:ring-gray-400">
                    <svg class="w-7 h-7 mx-auto" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 9H8a5 5 0 0 0 0 10h9m4-10-4-4m4 4-4 4" />
                    </svg>
                    <span
                        class="absolute block mb-px text-sm font-medium -translate-y-1/2 -start-[115px] top-1/2">recurring
                        expense</span>
                </button>
                <button type="button" x-data x-on:click="$dispatch('open-modal', { name: 'single-expense-form'})"
                    class="relative w-[52px] h-[52px] text-gray-500 bg-white rounded-full border border-gray-200 dark:border-gray-600 hover:text-gray-900 shadow-sm dark:hover:text-white dark:text-gray-400 hover:bg-gray-50 dark:bg-gray-700 dark:hover:bg-gray-600 focus:ring-4 focus:ring-gray-300 focus:outline-none dark:focus:ring-gray-400">
                    <svg class="w-7 h-7 mx-auto mt-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M2 7c0-1.10457.89543-2 2-2h16c1.1046 0 2 .89543 2 2v4c0 .5523-.4477 1-1 1s-1-.4477-1-1v-1H4v7h10c.5523 0 1 .4477 1 1s-.4477 1-1 1H4c-1.10457 0-2-.8954-2-2V7Z" />
                        <path fill="currentColor"
                            d="M5 14c0-.5523.44772-1 1-1h2c.55228 0 1 .4477 1 1s-.44772 1-1 1H6c-.55228 0-1-.4477-1-1Zm5 0c0-.5523.4477-1 1-1h4c.5523 0 1 .4477 1 1s-.4477 1-1 1h-4c-.5523 0-1-.4477-1-1Zm9-1c.5523 0 1 .4477 1 1v1h1c.5523 0 1 .4477 1 1s-.4477 1-1 1h-1v1c0 .5523-.4477 1-1 1s-1-.4477-1-1v-1h-1c-.5523 0-1-.4477-1-1s.4477-1 1-1h1v-1c0-.5523.4477-1 1-1Z" />
                    </svg>
                    <span
                        class="absolute block mb-px text-sm font-medium -translate-y-1/2 -start-[100px] top-1/2">single
                        expense</span>
                </button>
            </div>

            <button type="button" data-dial-toggle="speed-dial-menu-text-outside-button-square"
                aria-controls="speed-dial-menu-text-outside-button-square" aria-expanded="false"
                class="flex items-center justify-center text-white bg-purple-700 rounded-full w-14 h-14 hover:bg-purple-800 dark:bg-purple-600 dark:hover:bg-purple-700 focus:ring-4 focus:ring-purple-300 focus:outline-none dark:focus:ring-purple-800">
                <svg class="w-5 h-5 transition-transform group-hover:rotate-45" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 1v16M1 9h16" />
                </svg>
                <span class="sr-only">Open actions menu</span>
            </button>
        </div>
    </section>
</div>
