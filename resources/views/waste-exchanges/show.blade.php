@extends('layouts.app')

@section('title', 'Exchange Request Details')

@section('content')
    <div class="bg-gray-100 min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header with back button -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    @if(Auth::user()->companyProfile->id === $wasteExchange->supplier_company_id)
                        <a href="{{ route('waste-exchanges.received') }}"
                            class="flex items-center text-gray-600 hover:text-green-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Received Requests
                        </a>
                    @else
                        <a href="{{ route('waste-exchanges.sent') }}"
                            class="flex items-center text-gray-600 hover:text-green-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Sent Requests
                        </a>
                    @endif
                </div>

                <livewire:waste-exchange-actions :waste-exchange="$wasteExchange" />

            </div>

            <!-- Main content card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <!-- Title & Status Header -->
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                    <h1 class="text-xl font-bold text-gray-800">Exchange Request for {{ $wasteExchange->textileWaste->title }}</h1>
                    <div class="flex items-center">
                        @if($wasteExchange->status === 'requested')
                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                                Pending
                            </span>
                        @elseif($wasteExchange->status === 'accepted')
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                Accepted
                            </span>
                        @elseif($wasteExchange->status === 'completed')
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                Completed
                            </span>
                        @elseif($wasteExchange->status === 'cancelled')
                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                                Cancelled
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Exchange Information -->
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Exchange Information</h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <table class="w-full">
                                <tbody>
                                    <tr class="border-b border-gray-100">
                                        <td class="py-2 text-gray-500 w-1/3">Request Date</td>
                                        <td class="py-2 font-medium">{{ $wasteExchange->created_at->format('M d, Y h:i A') }}</td>
                                    </tr>
                                    <tr class="border-b border-gray-100">
                                        <td class="py-2 text-gray-500">Quantity</td>
                                        <td class="py-2 font-medium">{{ number_format($wasteExchange->quantity, 2) }} {{ $wasteExchange->textileWaste->unit }}</td>
                                    </tr>
                                    <tr class="border-b border-gray-100">
                                        <td class="py-2 text-gray-500">Status</td>
                                        <td class="py-2 font-medium capitalize">{{ $wasteExchange->status }}</td>
                                    </tr>
                                    @if($wasteExchange->final_price)
                                    <tr class="border-b border-gray-100">
                                        <td class="py-2 text-gray-500">Total Price</td>
                                        <td class="py-2 font-medium">${{ number_format($wasteExchange->final_price, 2) }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <table class="w-full">
                                <tbody>
                                    <tr class="border-b border-gray-100">
                                        <td class="py-2 text-gray-500 w-1/3">Supplier</td>
                                        <td class="py-2 font-medium">{{ $wasteExchange->supplierCompany->company_name }}</td>
                                    </tr>
                                    <tr class="border-b border-gray-100">
                                        <td class="py-2 text-gray-500">Requester</td>
                                        <td class="py-2 font-medium">{{ $wasteExchange->receiverCompany->company_name }}</td>
                                    </tr>
                                    @if($wasteExchange->exchange_date)
                                    <tr class="border-b border-gray-100">
                                        <td class="py-2 text-gray-500">Exchange Date</td>
                                        <td class="py-2 font-medium">{{ \Carbon\Carbon::parse($wasteExchange->exchange_date)->format('M d, Y h:i A') }}</td>
                                    </tr>
                                    @endif
                                    @if($wasteExchange->notes)
                                    <tr>
                                        <td class="py-2 text-gray-500">Notes</td>
                                        <td class="py-2">{{ $wasteExchange->notes }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Textile Waste Details -->
                @include('waste-exchanges.partials.textile-waste-details', ['textileWaste' => $wasteExchange->textileWaste])
            </div>

            <!-- Timeline Section -->
            <div class="mt-6 bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Exchange Timeline</h2>
                    @include('waste-exchanges.partials.exchange-timeline', ['exchange' => $wasteExchange])
                </div>
            </div>

            <!-- Metadata Footer -->
            <div class="mt-4 text-xs text-gray-500 flex justify-between">
                <div>Created: {{ $wasteExchange->created_at->format('d M Y, H:i') }}</div>
                <div>Last Updated: {{ $wasteExchange->updated_at->format('d M Y, H:i') }}</div>
                <div>ID: {{ $wasteExchange->id }}</div>
            </div>
        </div>
    </div>
@endsection
