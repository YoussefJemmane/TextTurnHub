<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TexTurn Hub') }} - @yield('title', 'Welcome')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @livewireStyles

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50">
    <!-- Simple Header -->
    <header class="absolute top-0 left-0 right-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ url('/') }}" class="flex items-center">
                    <img src="{{ asset('logo_white.png') }}" alt="TexTurn Hub Logo" class="h-[50px] mt-2">
                    <span class="text-2xl font-bold text-white">TexTurn Hub</span>
                </a>

                <!-- Navigation Links -->
                <nav class="space-x-4 text-white">
                    @if (Route::has('login') && Route::currentRouteName() != 'login')
                        <a href="{{ route('login') }}" class="font-medium hover:underline transition">Sign In</a>
                    @endif

                    @if (Route::has('register') && Route::currentRouteName() != 'register')
                        <a href="{{ route('register') }}" class="font-medium hover:underline transition">Register</a>
                    @endif
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <section
        class="flex items-center justify-center min-h-screen bg-gradient-to-r from-green-600 to-teal-600 text-white">
        @yield('content')
    </section>
    @livewireScripts

</body>

</html>
