@extends('layouts.app')

@section('title', 'Products')

@section('content')
    <div class="text-gray-800 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 px-4 sm:px-0">
                <h1 class="text-3xl font-bold text-gray-800">Products</h1>
                <p class="text-gray-600 mt-2">Manage your product inventory</p>
            </div>

            @if (session()->has('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-lg border border-gray-100">
                {{-- You can use a Livewire component or a table here --}}
                @livewire('product-table')
            </div>
        </div>
    </div>
@endsection
