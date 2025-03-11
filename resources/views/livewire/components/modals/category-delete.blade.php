<?php

use Livewire\Volt\Component;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public $name;
    public $budget;
    public $categoryId = null;

    public function mount(Category $category = null): void
    {
        if ($category != null) {
            //fix authentication error
            
            /*
            if ($category->user_id !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }
            */
            
            $this->categoryId = $category->id;
            $this->name = $category->name;
            $this->budget = $category->budget;
        }
    }

    public function deleteCategory(): void
    {
        $categoryAttributes['user_id'] = Auth::user()->id;

        if ($this->categoryId) {
            $category = Category::findOrFail($this->categoryId);
            if ($category->user_id !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

            $category->delete();
        }

        $this->redirect("/categories");
    }
}; ?>

<form wire:submit.prevent="deleteCategory">
    @csrf
    <div class="grid grid-col-2 items-center gap-1 mt-3">
        <x-input-label class="text-gray-200 left-center mt-2">Are you sure you want to delete this Category?</x-input-label>
    </div>

    <div class="flex items-center justify-between mt-7 gap-5">
        <button x-data x-on:click="$dispatch('close-modal')" type="button" class="text-gray-500 hover:text-gray-700 bg-transparent">Cancel</button>
        <x-delete-button>Delete Category</x-delete-button>
    </div>
</form>