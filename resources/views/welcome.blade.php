<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Clinic Management') }}</title>
    <!-- SVG Favicon -->
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
        @if (Route::has('login'))
            <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                @auth
                    <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>

                    <!-- @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                    @endif -->
                @endauth
            </div>
        @endif

        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <div class="flex justify-center">
                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 100 100">
                    <circle fill="#6366f1" cx="50" cy="50" r="45"/>
                    <path fill="#ffffff" d="M70,47H53V30c0-1.7-1.3-3-3-3s-3,1.3-3,3v17H30c-1.7,0-3,1.3-3,3s1.3,3,3,3h17v17c0,1.7,1.3,3,3,3s3-1.3,3-3V53h17
                        c1.7,0,3-1.3,3-3S71.7,47,70,47z"/>
                </svg>
            </div>

            <div class="mt-16">
                <div class="grid grid-cols-1 md:grid-cols-1 gap-6 lg:gap-8">
                    <div class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex flex-col">
                        <h2 class="text-xl font-semibold text-center text-gray-900 dark:text-white mb-4">Clinic Management System</h2>
                        <p class="text-gray-500 dark:text-gray-400 text-center leading-relaxed">
                            A comprehensive system for managing patients, sessions, and medication records in a clinic.
                        </p>
                        <div class="flex justify-center mt-8">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Go to Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Login to Begin
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>