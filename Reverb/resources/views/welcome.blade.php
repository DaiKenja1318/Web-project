<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-100">
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen">
            @if (Route::has('login'))
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-end">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ms-4 font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="max-w-7xl mx-auto p-6 lg:p-8 text-center">
                <div class="flex justify-center">
                    <x-application-logo class="h-16 w-auto text-gray-500" />
                </div>

                <div class="mt-8">
                    <h1 class="text-4xl font-bold text-gray-800">Welcome to Our Story Platform!</h1>
                    <p class="mt-4 text-lg text-gray-600">
                        Join our community to share your stories and connect with others.
                    </p>
                </div>

                <div class="mt-8 flex justify-center gap-4">
                     <a href="{{ route('login') }}" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 transition">
                        Log In
                    </a>
                     <a href="{{ route('register') }}" class="px-6 py-3 bg-white text-blue-600 font-semibold rounded-md shadow-md hover:bg-gray-50 transition">
                        Create an Account
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>