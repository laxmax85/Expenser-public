@props(['categories'])

<x-app-layout>

    <section>
        <x-default.modal name="newCategory" title="Create new category">
            @livewire('components.modals.category-form')
        </x-default.modal>
    </section>

    <div class="py-8">
        <div class="max-w-[1600px] mx-auto sm:px-6 lg:px-8 relative">
            <!-- Banner Image -->
            <img src="{{ asset('storage/images/purplebanner.jpg') }}" alt="Banner"
                class="w-full h-24 object-cover rounded-lg shadow-md">
            <h2
                class="font-semibold text-xl text-white dark:text-gray-200 text-left ml-16 absolute top-1/2 left-5 transform -translate-y-1/2">
                {{ __('Categories') }}
            </h2>
        </div>
    </div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- create a new Category -->
        <button x-data x-on:click="$dispatch('open-modal', {name: 'newCategory'})"
            class="w-full py-2 bg-purple-500 text-white font-semibold rounded-lg shadow hover:bg-purple-600 transition duration-300">
            Add new Category
        </button>

        <div class="py-12">
            <div class="grid grid-cols-3 gap-4 mx-10">
                @foreach ($categories as $category)
                    <a href="/categories/{{ $category->id }}"
                        class="max-w bg-gray-800 text-white rounded-lg shadow-lg p-4 hover:scale-105 hover:shadow-2xl transition-all duration-300 ease-in-out transform">
                        <div class="flex items-center space-x-2">
                            <div
                                class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-700 p-1 text-gray-800 dark:text-gray-400 ">
                                {{ svg('fluentui-' . $category->icon) }} <!-- Display the category icon -->
                            </div>
                            <!-- Title -->
                            <h3 class="text-lg font-semibold">{{ $category->name }}</h3>
                        </div>

                        <hr class="my-4 h-0.5 border-t-0 bg-neutral-100 dark:bg-white/10" />

                        <div class="mt-2 text-gray-300">
                            <p><strong>Budget:</strong> ${{ number_format($category->budget, 2) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
