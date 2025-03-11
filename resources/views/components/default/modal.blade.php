@props(['title', 'name'])

<div x-data="{ show: false, name: '{{ $name }}' }" x-show="show" x-cloak x-on:open-modal.window="show = ($event.detail.name === name)"
    x-on:close-modal.window="show = false" x-on:keydown.escape.window="show = false" x-transition.duration.300ms
    {{ $attributes(['class' => 'fixed inset-0 z-50 flex justify-center items-center']) }}>
    <div x-data x-on:click="show = false" class="bg-black/50 w-full h-full"></div>
    <div class="fixed w-full max-w-lg bg-gray-800 shadow-md rounded-xl px-8 pt-6 pb-8 mb-4">
        @if (isset($title))
            <h1 class="text-center text-2xl font-bold text-black dark:text-white">{{ $title }}</h1>
        @endif
        {{ $slot }}
    </div>
</div>
