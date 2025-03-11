@props(['categories'])

<x-app-layout>
    <div class="py-8">
        <div class="max-w-[1600px] mx-auto sm:px-6 lg:px-8 relative">
            <!-- Image -->
            <img src="{{ asset('storage/images/purplebanner.jpg') }}" alt="Banner"
                class="w-full h-24 object-cover rounded-lg shadow-md">

            <!-- Dashboard Text inside the Image, on the Left -->
            <h2
                class="font-semibold text-xl text-white dark:text-gray-200 text-left ml-16 absolute top-1/2 left-5 transform -translate-y-1/2">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </div>

    <div class="py-12">
        <div class="grid grid-cols-5 gap-4 mx-10">
            <div class="col-span-1 h-[500px] bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
                <h3 class="dark:text-white text-2xl">Categories</h3>
                <p class="dark:text-gray-500 text-lg">Quick links to all categories created</p>
                <hr class="my-4 h-0.5 border-t-0 bg-neutral-100 dark:bg-white/10" />
                <div>
                    <ul class="my-4 max-h-[365px] overflow-auto">
                        @foreach ($categories as $category)
                            <li
                                class="flex m-2 bg-gray-700 rounded-xl p-2 items-center justify-between group transition-transform duration-300 hover:scale-105 hover:bg-gray-600">
                                <a class="flex items-center gap-2 w-full h-full" href="/categories/{{ $category->id }}">
                                    <!-- SVG Icon with hover transition applied -->
                                    <div
                                        class="w-12 h-12 rounded-xl p-1 bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-400 ">
                                        {{ svg('fluentui-' . $category->icon) }} <!-- Display the category icon -->
                                    </div>
                                    <!-- Category Name with hover text color transition -->
                                    <p
                                        class="text-gray-500 dark:text-gray-400 group-hover:text-gray-300 transition-colors duration-300">
                                        {{ $category->name }}
                                    </p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="col-span-2 h-[500px] bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
                <h3 class="dark:text-white text-2xl">Spending this month</h3>
                <p class="dark:text-gray-500 text-lg">Spending accross all catorgies of the current month</p>
                <hr class="my-4 h-0.5 border-t-0 bg-neutral-100 dark:bg-white/10" />
                @livewire('components.charts.expense-donut-chart')
            </div>

            <div class="col-span-2 h-[500px] bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
                @livewire('components.charts.budget-spending-overview-chart')
            </div>

        </div>
    </div>
</x-app-layout>

<!-- Include ApexCharts library -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
