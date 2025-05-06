<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TexTurn Hub') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @livewireStyles
    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-green-600">TexTurn Hub</a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex space-x-8">
                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="text-gray-700 hover:text-green-600 font-medium">Dashboard</a>
                            @role('company')
                                <a href="{{ route('textile-waste.index') }}"
                                    class="text-gray-700 hover:text-green-600 font-medium">Textile Wastes</a>

                                <!-- Marketplace Dropdown -->
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" @click.away="open = false"
                                        class="text-gray-700 hover:text-green-600 font-medium inline-flex items-center">
                                        <span>Marketplace</span>
                                        <svg class="ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>

                                    <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                                        <div class="py-1">
                                            <a href="{{ route('marketplace.index') }}"
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Browse
                                                Marketplace</a>
                                            <a href="{{ route('waste-exchanges.sent') }}"
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sent
                                                Requests</a>
                                            <a href="{{ route('waste-exchanges.received') }}"
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Received
                                                Requests</a>
                                            <a href="{{ route('waste-exchanges.history') }}"
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Exchange
                                                History</a>
                                        </div>
                                    </div>
                                </div>
                            @endrole
                            <a href="{{ route('messaging.index') }}" class="text-gray-700 hover:text-green-600 font-medium flex items-center">
                                <span>Messanger</span>
                                @if ($unreadMessages > 0)
                                    <span class="ml-1 bg-green-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                        {{ $unreadMessages }}
                                    </span>
                                @endif
                            </a>
                            <a href="{{ route('profile.edit') }}"
                                class="text-gray-700 hover:text-green-600 font-medium">Profile</a>
                        @endauth
                    </div>

                    <div class="hidden md:flex items-center space-x-4">
                        @auth
                            <span class="text-gray-700 font-medium">{{ Auth::user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="bg-green-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-700">Log
                                    Out</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-green-600 font-medium">Sign
                                In</a>
                            <a href="{{ route('register') }}"
                                class="bg-green-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-700">Register</a>
                        @endauth
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="md:hidden">
                        <button @click="open = !open" class="text-gray-700 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path :class="{ 'hidden': open, 'block': !open }" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{ 'hidden': !open, 'block': open }" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12-12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div :class="{ 'block': open, 'hidden': !open }" class="hidden md:hidden bg-white border-t border-gray-200">
                <div class="py-2 space-y-1">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="block px-4 py-2 text-gray-700 hover:text-green-600">Dashboard</a>
                        @role('company')
                            <a href="{{ route('textile-waste.index') }}"
                                class="block px-4 py-2 text-gray-700 hover:text-green-600">Textile Wastes</a>

                            <!-- Mobile Marketplace Links -->
                            <div class="pl-4">
                                <div class="font-medium px-4 py-2 text-gray-700">Marketplace</div>
                                <a href="{{ route('marketplace.index') }}"
                                    class="block px-4 py-2 text-gray-700 hover:text-green-600">Browse Marketplace</a>
                                <a href="{{ route('waste-exchanges.sent') }}"
                                    class="block px-4 py-2 text-gray-700 hover:text-green-600">Sent Requests</a>
                                <a href="{{ route('waste-exchanges.received') }}"
                                    class="block px-4 py-2 text-gray-700 hover:text-green-600">Received Requests</a>
                                <a href="{{ route('waste-exchanges.history') }}"
                                    class="block px-4 py-2 text-gray-700 hover:text-green-600">Exchange History</a>
                            </div>
                        @endrole
                        <a href="{{ route('profile.edit') }}"
                            class="block px-4 py-2 text-gray-700 hover:text-green-600">Profile</a>
                    @endauth
                </div>
                @auth
                    <div class="border-t border-gray-200 py-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-4 py-2 text-gray-700 hover:text-green-600">Log
                                Out</button>
                        </form>
                    </div>
                @else
                    <div class="border-t border-gray-200 py-2">
                        <a href="{{ route('login') }}"
                            class="block w-full text-left px-4 py-2 text-gray-700 hover:text-green-600">Sign In</a>
                        <a href="{{ route('register') }}"
                            class="block w-full text-left px-4 py-2 text-gray-700 hover:text-green-600">Register</a>
                    </div>
                @endauth
            </div>
        </nav>

        <!-- Page Content -->
        <main class="flex-grow">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center text-gray-500 text-sm">
                    &copy; {{ date('Y') }} TexTurn Hub. All rights reserved.
                </div>
            </div>
        </footer>
    </div>
    @livewireScripts

</body>

</html>
