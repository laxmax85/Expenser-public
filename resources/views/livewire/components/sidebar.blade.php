<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    public string $activeTab;
    public bool $isSidebarCollapsed;

    public function mount(string $activeTab = 'dashboard', bool $isSidebarCollapsed = false)
    {
        $this->activeTab = $activeTab;
        $this->isSidebarCollapsed = $isSidebarCollapsed;
    }
}; ?>

<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.x.x/dist/cdn.min.js"></script>

<section>
    <x-default.modal name="logout" title="Are you sure you want to log out of your account?">
        @livewire('components.modals.logout-confirmation')
    </x-default.modal>
</section>

<div x-data="{
    open: $persist(false),
    currentPage: '{{ request()->path() }}',
    isCategoriesOpen: false
}" class="relative">
    <div :class="{ 'w-20': !open, 'w-56 overflow-hidden': open }"
        class="fixed top-4 left-4 z-40 h-[calc(100%-2rem)] bg-gray-800 border border-gray-600 text-white rounded-2xl flex flex-col select-none transition-all duration-300 ease-in-out">
        <div id="nav-header" class="relative w-full min-h-[80px] rounded-t-xl flex items-center px-4 gap-2">
            <button @click="open = !open; if (!open) isCategoriesOpen = false"
                class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-700 hover:bg-gray-600 transition-all">
                <svg :class="{ 'rotate-90': !open }" class="w-5 h-5 transition-transform duration-300"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <span :class="{ 'opacity-0 hidden': !open }" class="font-semibold text-xl transition-all duration-300">
                Expenser
            </span>
        </div>

        <div class="flex flex-col justify-between h-full items-center">
            <div class="overflow-hidden">
                <hr class="absolute left-4 w-[calc(100%-32px)] border-t border-gray-600" />

                <!-- Dashboard link -->
                <a :class="{ 'active bg-gray-700': currentPage === 'dashboard' }" @click="currentPage = 'dashboard'"
                    class="flex items-center p-4 mx-2 dark:text-gray-400 dark:hover:text-purple-500 dark:hover:bg-gray-700 rounded-xl cursor-pointer relative transition-all"
                    href="/dashboard">
                    <svg class="w-7 h-7" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M13.5 2c-.178 0-.356.013-.492.022l-.074.005a1 1 0 0 0-.934.998V11a1 1 0 0 0 1 1h7.975a1 1 0 0 0 .998-.934l.005-.074A7.04 7.04 0 0 0 22 10.5 8.5 8.5 0 0 0 13.5 2Z" />
                        <path
                            d="M11 6.025a1 1 0 0 0-1.065-.998 8.5 8.5 0 1 0 9.038 9.039A1 1 0 0 0 17.975 13H11V6.025Z" />
                    </svg>
                    <h3 x-show="open" class="ml-2 text-xl">Dashboard</h3>
                    <!-- Active state indentation and purple color -->
                    <div :class="{ 'pl-2 bg-purple-500 rounded-l-xl': currentPage === 'dashboard' }"
                        class="absolute inset-y-0 left-0 w-1.5 transition-all"></div>
                </a>

                <!-- Categories link -->
                <a :class="{ 'active bg-gray-700': currentPage === 'categories' }" @click="currentPage = 'categories'"
                    class="flex p-4 mx-2 dark:text-gray-400 dark:hover:text-purple-500 dark:hover:bg-gray-700 rounded-xl cursor-pointer relative transition-all"
                    href="/categories">
                    <svg class="w-7 h-7" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M4.857 3A1.857 1.857 0 0 0 3 4.857v4.286C3 10.169 3.831 11 4.857 11h4.286A1.857 1.857 0 0 0 11 9.143V4.857A1.857 1.857 0 0 0 9.143 3H4.857Zm10 0A1.857 1.857 0 0 0 13 4.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 21 9.143V4.857A1.857 1.857 0 0 0 19.143 3h-4.286Zm-10 10A1.857 1.857 0 0 0 3 14.857v4.286C3 20.169 3.831 21 4.857 21h4.286A1.857 1.857 0 0 0 11 19.143v-4.286A1.857 1.857 0 0 0 9.143 13H4.857Zm10 0A1.857 1.857 0 0 0 13 14.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 21 19.143v-4.286A1.857 1.857 0 0 0 19.143 13h-4.286Z"
                            clip-rule="evenodd" />
                    </svg>
                    <h3 x-show="open" class="ml-2 text-xl">Categories</h3>
                    <div :class="{ 'pl-2 bg-purple-500 rounded-l-xl': currentPage === 'categories' }"
                        class="absolute inset-y-0 left-0 w-1.5 transition-all"></div>
                </a>
            </div>

            <div>
                <hr class="absolute left-4 w-[calc(100%-32px)] border-t border-gray-600" />

                <!-- About link -->
                <a href="/about" :class="{ 'active bg-gray-700': currentPage === 'about' }"
                    @click="currentPage = 'about'"
                    class="flex p-4 mx-2 dark:text-gray-400 dark:hover:text-purple-500 dark:hover:bg-gray-700 rounded-xl cursor-pointer relative transition-all">
                    <svg class="w-7 h-7" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm9.408-5.5a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM10 10a1 1 0 1 0 0 2h1v3h-1a1 1 0 1 0 0 2h4a1 1 0 1 0 0-2h-1v-4a1 1 0 0 0-1-1h-2Z"
                            clip-rule="evenodd" />
                    </svg>
                    <h3 x-show="open" class="ml-2 text-xl">About</h3>
                    <div :class="{ 'pl-2 bg-purple-500 rounded-l-xl': currentPage === 'about' }"
                        class="absolute inset-y-0 left-0 w-1.5 transition-all"></div>
                </a>

                <!-- Account link -->
                <a :class="{ 'active bg-gray-700': currentPage === 'profile' }" @click="currentPage = 'profile'"
                    class="flex p-4 mx-2 dark:text-gray-400 dark:hover:text-purple-500 dark:hover:bg-gray-700 rounded-xl cursor-pointer relative transition-all"
                    href="/profile">
                    <svg class="w-7 h-7" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M12 20a7.966 7.966 0 0 1-5.002-1.756l.002.001v-.683c0-1.794 1.492-3.25 3.333-3.25h3.334c1.84 0 3.333 1.456 3.333 3.25v.683A7.966 7.966 0 0 1 12 20ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10c0 5.5-4.44 9.963-9.932 10h-.138C6.438 21.962 2 17.5 2 12Zm10-5c-1.84 0-3.333 1.455-3.333 3.25S10.159 13.5 12 13.5c1.84 0 3.333-1.455 3.333-3.25S13.841 7 12 7Z"
                            clip-rule="evenodd" />
                    </svg>
                    <h3 x-show="open" class="ml-2 text-xl">Account</h3>
                    <div :class="{ 'pl-2 bg-purple-500 rounded-l-xl': currentPage === 'profile' }"
                        class="absolute inset-y-0 left-0 w-1.5 transition-all"></div>
                </a>

                <!-- Logout link -->
                <button x-data x-on:click="$dispatch('open-modal', { name: 'logout'})"
                    class="flex p-4 mx-2 dark:text-gray-400 dark:hover:text-purple-500 dark:hover:bg-gray-700 rounded-xl cursor-pointer relative transition-all">
                    <svg class="w-7 h-7" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 12H4m12 0-4 4m4-4-4-4m3-4h2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3h-2" />
                    </svg>
                    <h3 x-show="open" class="ml-2 text-xl">Logout</h3>
                </button>
            </div>
        </div>
    </div>
</div>
