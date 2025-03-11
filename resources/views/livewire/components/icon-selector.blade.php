<?php

use Livewire\Volt\Component;

new class extends Component {
    public string $selectedIcon = 'food-20';
    public string $name = 'selectedIcon'; // Default name attribute
    public $value;

    public array $icons = [
        'food' => ['food-20', 'food-apple-20', 'food-pizza-20', 'food-cake-20', 'food-fish-20', 'food-carrot-20', 'food-chicken-leg-20'],
        'vehicles' => ['vehicle-bicycle-20', 'vehicle-car-profile-ltr-20', 'vehicle-subway-20', 'vehicle-bus-20', 'vehicle-truck-20', 'vehicle-truck-cube-20', 'vehicle-truck-profile-20'],
        'finances' => ['savings-20', 'people-money-20', 'person-money-20', 'money-20'],
        'paperwork' => ['form-multiple-20', 'document-20', 'document-edit-20'],
        'technology' => ['phone-desktop-20', 'phone-20'],
        'shopping' => ['building-shop-20', 'shopping-bag-20'],
        'hobbies' => ['xbox-controller-20', 'camera-20'],
        'other' => ['beach-20', 'star-20'],
    ];

    public function mount($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
        $this->selectedIcon = $value;
    }

    public function updatedSelectedIcon($value)
    {
        $this->dispatch('iconUpdated', $value);
    }
}; ?>

<div x-data="{ open: false }" @click.away="open = false" class="relative">
    <button @click.prevent="open = !open" class="text-gray-800 dark:text-gray-400">
        <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 p-1">
            {{ svg('fluentui-' . $selectedIcon) }}
        </div>
    </button>

    <div x-show="open" x-transition
        class="absolute bg-gray-800 max-h-[200px] w-[300px] overflow-y-auto rounded-xl p-2 border border-gray-600">
        @foreach ($icons as $category => $iconGroup)
            <div class="mb-4" x-data="{ open{{ ucfirst($category) }}: true }">
                <div class="flex justify-center mb-2">
                    <button class="text-left font-bold text-lg text-gray-800 dark:text-gray-400"
                        x-on:click="open{{ ucfirst($category) }} = !open{{ ucfirst($category) }}">
                        {{ ucfirst($category) }}
                        <span x-text="open{{ ucfirst($category) }} ? '-' : '+'"></span>
                    </button>
                </div>

                <div class="mt-2 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2 justify-center"
                    x-show="open{{ ucfirst($category) }}">

                    @foreach ($iconGroup as $icon)
                        <div class="cursor-pointer rounded-md text-gray-800 dark:text-gray-400 flex"
                            wire:click="$set('selectedIcon', '{{ $icon }}'); open = false"
                            title="{{ $icon }}">
                            <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 p-1">
                                {{ svg('fluentui-' . $icon) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
