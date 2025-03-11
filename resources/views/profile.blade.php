<x-app-layout>
    <div class="py-8">
        <div class="max-w-[1600px] mx-auto sm:px-6 lg:px-8 relative">
            <img src="{{ asset('storage/images/purplebanner.jpg') }}" alt="Banner"
                class="w-full h-24 object-cover rounded-lg shadow-md">

            <h2
                class="font-semibold text-xl text-white dark:text-gray-200 text-left ml-16 absolute top-1/2 left-5 transform -translate-y-1/2">
                {{ __('Profile') }}
            </h2>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <livewire:profile.update-password-form />
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
