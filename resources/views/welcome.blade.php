<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased font-sans">
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <div class="absolute w-full h-screen">
            <img id="background" class="w-full h-full object-cover" src="{{ asset('images/mainBackground.jpg') }}"
                alt="">
            <div class="absolute inset-0 bg-black opacity-40"></div>
        </div>

        <div
            class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">

            <main class="mt-6">
                <div class="flex items-center justify-center max-w-xs mx-auto p-5 mb-10">
                    <img class="" src="{{ asset('images/logo.svg') }}" alt="">
                </div>
                <div class="max-w-4xl text-center">
                    <h1 class="text-5xl text-white font-bold">Managing expenes made easy</h1>
                    <p class="text-lg text-gray-300">Track every expense, stay on budget, and take control of your financial future — effortlessly</p>
                </div>

                <div class="flex items-center justify-center gap-5 mt-10">
                    @auth
                        <x-primary-link-button href="/login">{{ __('Dashboard') }}</x-primary-link-button>
                    @endauth

                    @guest
                        <x-secondary-link-button href="/register">{{ __('Sign up') }}</x-secondary-link-button>
                        <x-primary-link-button href="/login">{{ __('Log in') }}</x-primary-link-button>
                    @endguest
                </div>
            </main>

            <footer class="py-16 text-center text-sm text-black dark:text-white/70">
                Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
            </footer>
        </div>
    </div>
</body>

</html>
