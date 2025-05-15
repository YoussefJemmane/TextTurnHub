@extends('layouts.user')

@section('title', 'Welcome to TexTurn Hub')

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-gray-900 h-[500px]">
        <div class="absolute inset-0">
            <img  alt="Hero background" class="w-full h-full object-cover opacity-50">
        </div>
        <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
                Welcome to TexTurn Hub
            </h1>
            <p class="mt-6 text-xl text-gray-300 max-w-3xl">
                Discover amazing products from local manufacturers and artisans.
            </p>
            <div class="mt-10">
                <a href="#featured-products" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Shop Now
                </a>
            </div>
        </div>
    </div>

    <!-- Featured Categories -->
    <div class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold tracking-tight text-gray-900">
                Popular Categories
            </h2>
            <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($categories ?? [] as $category)
                    <div class="group relative">
                        
                        <h3 class="mt-4 text-lg font-medium text-gray-900">
                            <a href="#">{{ $category }}</a>
                        </h3>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Featured Products -->
    <div id="featured-products" class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold tracking-tight text-gray-900">
                Featured Products
            </h2>
            <div class="mt-6 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($featuredProducts ?? [] as $product)
                    <div class="group relative">
                        <div class="aspect-w-1 aspect-h-1 rounded-lg overflow-hidden">
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" 
                                 class="w-full h-full object-center object-cover group-hover:opacity-75">
                        </div>
                        <div class="mt-4 flex justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">
                                    <a href="#">{{ $product->name }}</a>
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">{{ $product->category }}</p>
                            </div>
                            <p class="text-lg font-medium text-gray-900">${{ number_format($product->price, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-y-12 gap-x-6 sm:grid-cols-2 lg:grid-cols-3">
                <div class="text-center">
                    <span class="rounded-lg inline-flex p-3 bg-blue-50 text-blue-700">
                        <!-- Heroicon name: shipping -->
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                    </span>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Free Shipping</h3>
                    <p class="mt-2 text-base text-gray-500">On orders over $100</p>
                </div>

                <div class="text-center">
                    <span class="rounded-lg inline-flex p-3 bg-blue-50 text-blue-700">
                        <!-- Heroicon name: support -->
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </span>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">24/7 Support</h3>
                    <p class="mt-2 text-base text-gray-500">Here to help anytime</p>
                </div>

                <div class="text-center">
                    <span class="rounded-lg inline-flex p-3 bg-blue-50 text-blue-700">
                        <!-- Heroicon name: shield-check -->
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </span>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Secure Payments</h3>
                    <p class="mt-2 text-base text-gray-500">100% protected transactions</p>
                </div>
            </div>
        </div>
    </div>
@endsection