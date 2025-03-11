<?php

use Livewire\Volt\Component;

new class extends Component {
    public $id;
    public $data;

    public function mount($id, $data)
    {
        $this->id = $id;
        $this->data = $data;
    }
}; ?>

<div>
    <div class="flex justify-between pb-4 mb-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center me-3">
                <x-fluentui-money-20 class="text-gray-800 dark:text-gray-400 m-1" />
            </div>
            <div>
                <h5 class="leading-none text-2xl font-bold text-gray-900 dark:text-white pb-1">
                    €{{ number_format(collect($data['Spending']['data'])->last()['y'] ?? 0, 2, ',', '.') }}
                </h5>    
                <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Spent this month</p>
            </div>
        </div>
    </div>

    @livewire('components.charts.mixed-bar-chart-with-line', ['id' => $id, 'data' => $data], key($id))
</div>
