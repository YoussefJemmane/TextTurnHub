<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - @yield('title')</title>
    <!-- Include Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Add Font Awesome for better icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Add Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .dropdown-menu {
            transform: translateY(10px);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        .dropdown:hover .dropdown-menu {
            transform: translateY(0);
            opacity: 1;
            visibility: visible;
        }
        .nav-link {
            position: relative;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: #3b82f6;
            transition: width 0.3s ease;
        }
        .nav-link:hover::after {
            width: 100%;
        }
        @media (max-width: 768px) {
            .mobile-menu-hidden {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Navigation Bar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <span class="text-xl sm:text-2xl font-bold text-blue-600">TexTurn<span class="text-gray-800">Hub</span></span>
                </a>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-button" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <!-- Categories Dropdown -->
                    <div class="dropdown relative">
                        <button class="flex items-center space-x-1 nav-link text-gray-700 hover:text-blue-600 transition">
                            <span>Categories</span>
                            <i class="fas fa-chevron-down text-xs ml-1"></i>
                        </button>
                        <div class="dropdown-menu absolute w-56 bg-white shadow-lg rounded-md py-2 mt-1 z-10">
                            @foreach($categories ?? [] as $category)
                                <a class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                    {{ $category }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Search Bar -->
                    <form method="GET" class="relative">
                        <input type="text" name="query" placeholder="Search products..." 
                               class="w-60 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <button type="submit" class="absolute right-3 top-2.5 text-gray-500 hover:text-blue-600">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>

                    <!-- Cart -->
                    <a class="relative group">
                        <span class="nav-link text-gray-700 hover:text-blue-600 transition flex items-center">
                            <i class="fas fa-shopping-cart text-xl"></i>
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">
                                {{-- {{ Cart::count() ?? 0 }} --}}
                                3
                            </span>
                        </span>
                    </a>

                    <!-- User Profile Dropdown -->
                    <div class="dropdown relative">
                        <button class="flex items-center space-x-2 nav-link text-gray-700 hover:text-blue-600 transition">
                            <img src="{{ Auth::user()->avatar ?? asset('images/default-avatar.png') }}" 
                                 class="w-8 h-8 rounded-full object-cover border-2 border-gray-200">
                            <span>{{ Auth::user()->name ?? 'Guest' }}</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div class="dropdown-menu absolute right-0 w-56 bg-white shadow-lg rounded-md py-2 mt-1 z-10">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                <i class="fas fa-user mr-2"></i> Profile
                            </a>
                            <a class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                <i class="fas fa-shopping-bag mr-2"></i> Orders
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 transition">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div id="mobile-menu" class="mobile-menu-hidden md:hidden bg-white border-t border-gray-200">
            <div class="px-4 py-3 space-y-3">
                <!-- Mobile Search -->
                <form method="GET" class="relative">
                    <input type="text" name="query" placeholder="Search products..." 
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="absolute right-3 top-2.5 text-gray-500">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                
                <!-- Mobile Categories -->
                <div class="py-2">
                    <button id="mobile-categories-button" class="flex items-center justify-between w-full py-2 text-gray-700">
                        <span>Categories</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <div id="mobile-categories-menu" class="hidden pl-4 mt-1 space-y-1">
                        @foreach($categories ?? [] as $category)
                            <a class="block py-2 text-gray-700">
                                {{ $category }}
                            </a>
                        @endforeach
                    </div>
                </div>
                
                <!-- Mobile Cart -->
                <a class="flex items-center justify-between py-2 text-gray-700">
                    <span>Cart</span>
                    <span class="bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">
                        {{-- {{ Cart::count() ?? 0 }} --}}
                        3
                    </span>
                </a>
                
                <!-- Mobile Profile Links -->
                <div class="border-t border-gray-200 pt-2">
                    <div class="flex items-center space-x-3 py-2">
                        <img src="{{ Auth::user()->avatar ?? asset('images/default-avatar.png') }}" 
                             class="w-8 h-8 rounded-full object-cover">
                        <span class="font-medium">{{ Auth::user()->name ?? 'Guest' }}</span>
                    </div>
                    <div class="pl-2 space-y-1 mt-2">
                        <a href="{{ route('profile.edit') }}" class="block py-2 text-gray-700">
                            <i class="fas fa-user mr-2"></i> Profile
                        </a>
                        <a class="block py-2 text-gray-700">
                            <i class="fas fa-shopping-bag mr-2"></i> Orders
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="block py-2">
                            @csrf
                            <button type="submit" class="text-gray-700 flex items-center">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">About Us</h3>
                    <p class="text-gray-400 mb-4">Your trusted source for quality products. We provide the best selection of items at competitive prices.</p>
                    <div class="flex space-x-4 mt-6">
                        <a class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-facebook-f text-xl"></i>
                        </a>
                        <a class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('home') }}" class="text-gray-400 hover:text-white transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i> Home
                            </a>
                        </li>
                        <li>
                            <a class="text-gray-400 hover:text-white transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i> Shop
                            </a>
                        </li>
                        <li>
                            <a class="text-gray-400 hover:text-white transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i> Contact
                            </a>
                        </li>
                        <li>
                            <a class="text-gray-400 hover:text-white transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i> FAQ
                            </a>
                        </li>
                        <li>
                            <a class="text-gray-400 hover:text-white transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i> Privacy Policy
                            </a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact Info</h3>
                    <ul class="space-y-3 text-gray-400">
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-blue-400"></i>
                            <span>info@texthub.com</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone-alt mr-3 text-blue-400"></i>
                            <span>(123) 456-7890</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mr-3 mt-1 text-blue-400"></i>
                            <span>123 Street, City, Country</span>
                        </li>
                    </ul>
                    <div class="mt-6">
                        <h4 class="text-sm font-medium mb-2">Subscribe to our newsletter</h4>
                        <form class="flex">
                            <input type="email" placeholder="Your email" 
                                   class="px-4 py-2 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-r-lg transition">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-gray-700 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('mobile-menu-hidden');
        });

        // Mobile categories dropdown
        document.getElementById('mobile-categories-button').addEventListener('click', function() {
            const categoriesMenu = document.getElementById('mobile-categories-menu');
            categoriesMenu.classList.toggle('hidden');
            
            const icon = this.querySelector('i');
            if (icon.classList.contains('fa-chevron-down')) {
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        });
    </script>
</body>
</html>