<?php

use Livewire\Volt\Component;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public $name;
    public $description;
    public $budget;
    public $icon = 'food-20'; 
    public $categoryId = null;

    protected $listeners = ['iconUpdated' => 'updateIcon'];

    public function updateIcon($value)
    {
        $this->icon = $value;
    }

    public function mount($category = null): void
    {
        if ($category != null) {

            if ($category->user_id !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

            $this->categoryId = $category->id;
            $this->name = $category->name;
            $this->description = $category->description;
            $this->budget = $category->budget;
            $this->icon = $category->icon ?? 'food-20';
        }
    }

    public function store(): void
    {
        $attributes = $this->validate([
            'name' => ['required', 'max:50'],
            'budget' => ['required'],
            'icon' => ['required'],
            'description' => ['string'],
        ]);

        $attributes['user_id'] = Auth::user()->id;

        if ($this->categoryId) {
            $category = Category::findOrFail($this->categoryId);
            if ($category->user_id !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

            $category = DB::transaction(
                function () use ($attributes, $category) {

                    $category->name = $attributes['name'];
                    $category->description = $attributes['description'];
                    $category->budget = $attributes['budget'];
                    $category->icon = $attributes['icon'];

                    $category->save();
                    return $category;
                }
            );
            
        } else {
            $category = DB::transaction(
                function () use ($attributes) {
                    $category = new Category;

                    $category->name = $attributes['name'];
                    $category->description = $attributes['description'];
                    $category->budget = $attributes['budget'];
                    $category->icon = $attributes['icon'];
                    $category->user_id = $attributes['user_id'];

                    $category->save();
                    return $category;
                }
            );
        }

        $this->redirect("/categories/{$category->id}");
    }
};
?>

<div>
    <form wire:submit.prevent="store">
        @csrf
        <div class="grid grid-col-2 items-center mt-3">
            <x-input-label class="text-gray-200 left-center">Category Title</x-input-label>
            <div class="flex items-center gap-2 mt-1 w-full">
                @livewire('components.icon-selector', ['name' => 'icon', 'value' => $icon])

                <x-text-input id="name" wire:model="name" class="text-gray-400" placeholder="Enter category name"
                    value="{{ $name }}" />
            </div>

            <x-input-label class="text-gray-200 left-center mt-2">Description</x-input-label>
            <x-textarea-input id="budget" wire:model="description" type="text" min=0 step=0.01 class="text-gray-400"
                placeholder="Enter category budget" value="{{ $description }}" />

            <x-input-label class="text-gray-200 left-center mt-2">Budget</x-input-label>
            <x-text-input id="budget" wire:model="budget" type="number" min=0 step=0.01 class="text-gray-400"
                placeholder="Enter category budget" value="{{ $budget }}" />
        </div>

        <div class="flex items-center justify-between mt-7 gap-5">
            <button x-data x-on:click="$dispatch('close-modal')" type="button"
                class="text-gray-500 hover:text-gray-700 bg-transparent">Cancel</button>
            <x-primary-button>{{ $categoryId ? __('Update Category') : __('Add new Category') }}</x-primary-button>
        </div>
    </form>
</div>
