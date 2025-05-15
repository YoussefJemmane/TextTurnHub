@extends('layouts.app')

@section('title', 'Product Details')

@section('content')
    <div
        class="bg-gray-100 min-h-screen py-8"
        x-data="{ openContactModal: false, selectedImage: null }"
        x-init="openContactModal = false"
    >
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header with back button -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <a href="{{ route('products.index') }}"
                        class="flex items-center text-gray-600 hover:text-green-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Products
                    </a>
                </div>

                <div class="flex space-x-3">
                    @if(Auth::user()->artisanProfile && $product->artisan_profile_id == Auth::user()->artisanProfile->id)
                        <!-- Owner actions - Edit and Delete -->
                        <a href="{{ route('products.edit', $product) }}"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-medium transition flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>

                        <form method="POST" class="inline-block" action="{{ route('products.destroy', $product->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-lg font-medium transition flex items-center"
                                onclick="return confirm('Are you sure you want to delete this product?')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </button>
                        </form>
                    @else
                        <!-- Non-owner actions - Contact Artisan -->
                        <button @click="openContactModal = true"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                            Contact Artisan
                        </button>
                    @endif
                </div>
            </div>

            <!-- Main content card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <!-- Title & Status Header -->
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                    <h1 class="text-xl font-bold text-gray-800">{{ $product->name }}</h1>
                    <div class="flex items-center">
                        @if ($product->stock > 0)
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">In Stock</span>
                        @else
                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">Out of Stock</span>
                        @endif
                    </div>
                </div>

                <!-- Basic Information Section -->
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <div class="text-sm text-gray-500">Category</div>
                                <div>{{ $product->category }}</div>
                            </div>

                            <div class="mb-4">
                                <div class="text-sm text-gray-500">Price</div>
                                <div class="text-lg font-semibold text-green-600">MAD {{ number_format($product->price, 2) }}</div>
                            </div>

                            <div class="mb-4">
                                <div class="text-sm text-gray-500">Stock</div>
                                <div>{{ $product->stock }} {{ $product->unit }}</div>
                            </div>
                        </div>

                        <div>
                            <div class="mb-4">
                                <div class="text-sm text-gray-500">Description</div>
                                <div class="text-gray-700">{{ $product->description }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Specifications Section -->
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Specifications</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <div class="text-sm text-gray-500">Material</div>
                                <div>{{ $product->material ?? 'Not specified' }}</div>
                            </div>

                            <div class="mb-4">
                                <div class="text-sm text-gray-500">Color</div>
                                <div>{{ $product->color ?? 'Not specified' }}</div>
                            </div>
                        </div>

                        <div>
                            <div class="mb-4">
                                <div class="text-sm text-gray-500">Created By</div>
                                <div>{{ $product->artisanProfile->user->name }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Images Section -->
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Product Images</h2>

                    @if ($product->image)
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="overflow-hidden rounded-lg bg-gray-100">
                                <img
                                    src="{{ asset('storage/' . $product->image) }}"
                                    alt="Product Image"
                                    class="w-full h-32 object-cover cursor-pointer transition duration-300 ease-in-out hover:scale-105"
                                    @click="selectedImage = '{{ asset('storage/' . $product->image) }}'"
                                >
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500">No images uploaded.</p>
                    @endif

                    <!-- Image Modal -->
                    <div class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50"
                        x-show="selectedImage"
                        x-cloak
                        x-transition
                        @keydown.escape.window="selectedImage = null">
                        <div class="relative">
                            <button @click="selectedImage = null"
                                class="absolute top-2 right-2 text-white text-2xl font-bold bg-black bg-opacity-40 rounded-full w-10 h-10 flex items-center justify-center hover:bg-opacity-70 transition"
                                aria-label="Close">&times;</button>
                            <img :src="selectedImage" class="max-w-3xl max-h-[90vh] rounded shadow-lg">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Artisan Modal -->
            <div
                x-cloak
                x-show="openContactModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                @click.self="openContactModal = false"
                @keydown.escape.window="openContactModal = false"
            >
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                    <div class="p-5 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-800">Contact {{ $product->artisanProfile->user->name }}</h3>
                            <button @click="openContactModal = false" class="text-gray-400 hover:text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <form action="{{ route('messaging.create') }}" method="POST">
                        @csrf
                        <input type="hidden" name="recipient_id" value="{{ $product->artisanProfile->user_id }}">
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div class="p-5">
                            <div class="mb-4">
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                                <textarea
                                    name="message"
                                    id="message"
                                    rows="4"
                                    class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    placeholder="Enter your message to the artisan..."
                                    required
                                ></textarea>
                            </div>
                        </div>

                        <div class="px-5 py-3 bg-gray-50 text-right rounded-b-lg">
                            <button type="button" @click="openContactModal = false" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-medium transition mr-2">
                                Cancel
                            </button>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Metadata Footer -->
            <div class="mt-4 text-xs text-gray-500 flex justify-between">
                <div>Created: {{ $product->created_at->format('d M Y, H:i') }}</div>
                <div>Last Updated: {{ $product->updated_at->format('d M Y, H:i') }}</div>
                <div>ID: {{ $product->id }}</div>
            </div>
        </div>
    </div>
@endsection