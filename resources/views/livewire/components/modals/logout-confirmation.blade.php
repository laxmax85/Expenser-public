<?php

use Livewire\Volt\Component;
use App\Livewire\Actions\Logout;

new class extends Component {
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<form wire:submit.prevent="logout">
    <div class="flex items-center justify-end mt-5 gap-5">
        <button x-data x-on:click="$dispatch('close-modal')" type="button"
            class="text-gray-500 hover:text-gray-700 bg-transparent">Cancel</button>

        <!-- Delete button -->
        <x-primary-button>{{ __('Logout') }}</x-primary-button>
    </div>
</form>
