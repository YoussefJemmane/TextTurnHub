@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-2xl font-semibold text-gray-800">Request Textile Waste Exchange</h2>
                    <a href="{{ route('marketplace.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Back to Marketplace
                    </a>
                </div>

                @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
                @endif

                <div class="flex flex-col md:flex-row gap-6">
                    <!-- Textile Waste Details -->
                    <div class="md:w-1/2 bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Textile Waste Details</h3>
                        
                        @if(!empty($textileWaste->images) && is_array(json_decode($textileWaste->images)))
                        <div class="mb-4 h-48 overflow-hidden rounded-lg">
                            <img src="{{ Storage::url(json_decode($textileWaste->images)[0]) }}" 
                                alt="{{ $textileWaste->title }}" 
                                class="w-full h-full object-cover">
                        </div>
                        @endif
                        
                        <h4 class="text-xl font-semibold mb-2">{{ $textileWaste->title }}</h4>
                        
                        <p class="text-gray-600 mb-4">{{ $textileWaste->description }}</p>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-sm text-gray-500">Type</p>
                                <p class="font-medium capitalize">{{ $textileWaste->waste_type }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500">Material</p>
                                <p class="font-medium">{{ $textileWaste->material_type }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500">Available Quantity</p>
                                <p class="font-medium">{{ number_format($textileWaste->quantity, 2) }} {{ $textileWaste->unit }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500">Condition</p>
                                <p class="font-medium">{{ $textileWaste->condition }}</p>
                            </div>
                            
                            @if($textileWaste->minimum_order_quantity)
                            <div>
                                <p class="text-sm text-gray-500">Minimum Order</p>
                                <p class="font-medium">{{ number_format($textileWaste->minimum_order_quantity, 2) }} {{ $textileWaste->unit }}</p>
                            </div>
                            @endif
                            
                            @if($textileWaste->price_per_unit)
                            <div>
                                <p class="text-sm text-gray-500">Price</p>
                                <p class="font-medium">${{ number_format($textileWaste->price_per_unit, 2) }}/{{ $textileWaste->unit }}</p>
                            </div>
                            @endif
                        </div>
                        
                        <div class="pt-4 border-t border-gray-200">
                            <p class="text-sm text-gray-500 mb-1">Supplier</p>
                            <p class="font-medium">{{ $textileWaste->company->company_name }}</p>
                            <p class="text-sm text-gray-500 mt-2">Location</p>
                            <p class="font-medium">{{ $textileWaste->location }}</p>
                        </div>
                    </div>
                    
                    <!-- Exchange Request Form -->
                    <div class="md:w-1/2">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Exchange Request Details</h3>
                        
                        <form action="{{ route('waste-exchanges.store', $textileWaste) }}" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">
                                    Quantity Requested ({{ $textileWaste->unit }})
                                </label>
                                <input type="number" name="quantity" id="quantity" 
                                    min="{{ $textileWaste->minimum_order_quantity ?? 0.01 }}" 
                                    max="{{ $textileWaste->quantity }}" 
                                    step="0.01"
                                    value="{{ old('quantity', $textileWaste->minimum_order_quantity ?? ($textileWaste->quantity / 2)) }}" 
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('quantity') border-red-300 @enderror"
                                    required>
                                
                                @error('quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                
                                <p class="mt-1 text-sm text-gray-500">
                                    Available: {{ number_format($textileWaste->quantity, 2) }} {{ $textileWaste->unit }}
                                    @if($textileWaste->minimum_order_quantity)
                                    (Minimum order: {{ number_format($textileWaste->minimum_order_quantity, 2) }} {{ $textileWaste->unit }})
                                    @endif
                                </p>
                            </div>
                            
                            @if($textileWaste->price_per_unit)
                            <div class="mb-4 p-3 bg-gray-50 rounded-md">
                                <p class="text-sm font-medium text-gray-700">Price Calculation</p>
                                <div class="flex justify-between mt-2">
                                    <span>{{ $textileWaste->price_per_unit }} per {{ $textileWaste->unit }} × <span id="selected-quantity">0</span> {{ $textileWaste->unit }}</span>
                                    <span>$<span id="total-price">0.00</span></span>
                                </div>
                            </div>
                            @endif
                            
                            <div class="mb-6">
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                    Notes (Optional)
                                </label>
                                <textarea name="notes" id="notes" rows="3" 
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('notes') border-red-300 @enderror"
                                    placeholder="Add any specific requirements or questions for the supplier">{{ old('notes') }}</textarea>
                                
                                @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('marketplace.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Cancel
                                </a>
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    Submit Exchange Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($textileWaste->price_per_unit)
<script>
    // Update total price calculation when quantity changes
    document.getElementById('quantity').addEventListener('input', function() {
        const quantity = parseFloat(this.value) || 0;
        const pricePerUnit = {{ $textileWaste->price_per_unit }};
        const totalPrice = (quantity * pricePerUnit).toFixed(2);
        
        document.getElementById('selected-quantity').textContent = quantity;
        document.getElementById('total-price').textContent = totalPrice;
    });
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInput = document.getElementById('quantity');
        const event = new Event('input');
        quantityInput.dispatchEvent(event);
    });
</script>
@endif

@endsection
