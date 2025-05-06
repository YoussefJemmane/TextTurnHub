@extends('layouts.app')

@section('title', 'Textile Waste Marketplace')

@section('content')
<div class="text-gray-800 py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 px-4 sm:px-0">
            <h1 class="text-3xl font-bold text-gray-800">Textile Waste Marketplace</h1>
            <p class="text-gray-600 mt-2">Find and request textile waste materials</p>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 mb-8">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-700 mb-4">Filter Options</h3>
                <form action="{{ route('marketplace.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="waste_type" class="block text-sm font-medium text-gray-700">Waste Type</label>
                        <select id="waste_type" name="waste_type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-600 focus:border-green-600 sm:text-sm rounded-lg">
                            <option value="">All Types</option>
                            <option value="fabric" {{ request('waste_type') == 'fabric' ? 'selected' : '' }}>Fabric</option>
                            <option value="yarn" {{ request('waste_type') == 'yarn' ? 'selected' : '' }}>Yarn</option>
                            <option value="offcuts" {{ request('waste_type') == 'offcuts' ? 'selected' : '' }}>Offcuts</option>
                            <option value="scraps" {{ request('waste_type') == 'scraps' ? 'selected' : '' }}>Scraps</option>
                            <option value="other" {{ request('waste_type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div>
                        <label for="material_type" class="block text-sm font-medium text-gray-700">Material Type</label>
                        <input type="text" name="material_type" id="material_type" value="{{ request('material_type') }}" class="mt-1 focus:ring-green-600 focus:border-green-600 block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg">
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                        <input type="text" name="location" id="location" value="{{ request('location') }}" class="mt-1 focus:ring-green-600 focus:border-green-600 block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg">
                    </div>

                    <div class="md:col-span-3 flex justify-end space-x-3">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Apply Filters
                        </button>
                        <a href="{{ route('marketplace.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg border border-gray-100">
            <div class="p-6">

                <!-- Textile Wastes Grid -->
                @if($textileWastes->isEmpty())
                <div class="text-center py-12">
                    <h3 class="text-lg font-medium text-gray-500">No textile wastes available at the moment.</h3>
                    <p class="mt-2 text-sm text-gray-400">Check back later or adjust your filter criteria.</p>
                </div>
                @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($textileWastes as $textileWaste)
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
                            <!-- Image -->
                            <div class="h-48 bg-gray-200 overflow-hidden">
                                @if(!empty($textileWaste->images))
                                    <img src="{{ Storage::url($textileWaste->images) }}"
                                        alt="{{ $textileWaste->title }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="flex items-center justify-center h-full bg-gray-100">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-800 truncate">{{ $textileWaste->title }}</h3>

                                <div class="mt-2 space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Type</span>
                                        <span class="text-sm font-medium capitalize">{{ $textileWaste->waste_type }}</span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Material</span>
                                        <span class="text-sm font-medium">{{ $textileWaste->material_type }}</span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Quantity</span>
                                        <span class="text-sm font-medium">{{ number_format($textileWaste->quantity, 2) }} {{ $textileWaste->unit }}</span>
                                    </div>

                                    @if($textileWaste->price_per_unit)
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Price</span>
                                        <span class="text-sm font-medium">${{ number_format($textileWaste->price_per_unit, 2) }}/{{ $textileWaste->unit }}</span>
                                    </div>
                                    @endif

                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Location</span>
                                        <span class="text-sm font-medium">{{ $textileWaste->location }}</span>
                                    </div>
                                </div>

                                <div class="mt-4 flex justify-between items-center">
                                    <a href="{{ route('textile-waste.show', $textileWaste) }}" class="text-sm text-blue-600 hover:text-blue-900 transition">
                                        View Details
                                    </a>

                                    <a href="{{ route('waste-exchanges.create', $textileWaste) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Request Exchange
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $textileWastes->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
