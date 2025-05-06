@extends('layouts.app')

@section('title', 'Exchange Requests Received')

@section('content')
    <div class="text-gray-800 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 px-4 sm:px-0">
                <h1 class="text-3xl font-bold text-gray-800">Exchange Requests Received</h1>
                <p class="text-gray-600 mt-2">Manage exchange requests for your textile waste materials</p>
            </div>



            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-lg border border-gray-100">
                <div class="p-6">
                    <!-- Search -->
                    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center space-y-4 md:space-y-0">
                        <div class="relative md:w-1/3">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="search"
                                class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-200 focus:ring-green-600 focus:border-green-600 text-gray-600"
                                placeholder="Search requests...">
                        </div>
                    </div>

                    <!-- Exchange Requests List -->
                    @if ($exchangeRequests->isEmpty())
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                </path>
                            </svg>
                            <h3 class="mt-2 text-lg font-medium text-gray-900">No exchange requests received</h3>
                            <p class="mt-1 text-sm text-gray-500">You haven't received any exchange requests yet.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Waste Item
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Requester
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Quantity
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date Requested
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($exchangeRequests as $request)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        @if (!empty($request->textileWaste->images) && is_array(json_decode($request->textileWaste->images)))
                                                            <img class="h-10 w-10 rounded-full object-cover"
                                                                src="{{ Storage::url(json_decode($request->textileWaste->images)[0]) }}"
                                                                alt="">
                                                        @else
                                                            <div
                                                                class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                                <svg class="h-6 w-6 text-gray-400" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="font-medium text-gray-900">
                                                            {{ $request->textileWaste->title }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ ucfirst($request->textileWaste->waste_type) }} -
                                                            {{ $request->textileWaste->material_type }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ $request->receiverCompany->company_name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ number_format($request->quantity, 2) }}
                                                    {{ $request->textileWaste->unit }}</div>
                                                @if ($request->final_price)
                                                    <div class="text-sm text-gray-500">
                                                        ${{ number_format($request->final_price, 2) }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ $request->created_at->format('M d, Y') }}</div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $request->created_at->format('h:i A') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($request->status === 'requested')
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Requested
                                                    </span>
                                                @elseif($request->status === 'accepted')
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        Accepted
                                                    </span>
                                                @elseif($request->status === 'completed')
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Completed
                                                    </span>
                                                @elseif($request->status === 'cancelled')
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Cancelled
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex items-center space-x-3">
                                                    <!-- View Icon -->
                                                    <a href="{{ route('waste-exchanges.show', $request) }}"
                                                        class="text-blue-600 hover:text-blue-900 transition"
                                                        title="View Details">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>

                                                    @if ($request->status === 'requested')
                                                        <div class="flex items-center space-x-3">
                                                            <!-- Accept Icon -->
                                                            <form action="{{ route('waste-exchanges.accept', $request) }}"
                                                                method="POST" class="inline">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="text-green-600 hover:text-green-800 transition"
                                                                    title="Accept Request"
                                                                    onclick="return confirm('Are you sure you want to accept this request?')">
                                                                    <svg class="w-5 h-5" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M5 13l4 4L19 7" />
                                                                    </svg>
                                                                </button>
                                                            </form>

                                                            <!-- Reject Icon -->
                                                            <form action="{{ route('waste-exchanges.reject', $request) }}"
                                                                method="POST" class="inline">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="text-red-600 hover:text-red-800 transition"
                                                                    title="Reject Request"
                                                                    onclick="return confirm('Are you sure you want to reject this request?')">
                                                                    <svg class="w-5 h-5" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M6 18L18 6M6 6l12 12" />
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @elseif($request->status === 'accepted')
                                                        <!-- Complete Icon -->
                                                        <form action="{{ route('waste-exchanges.complete', $request) }}"
                                                            method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit"
                                                                class="text-green-600 hover:text-green-800 transition"
                                                                title="Mark as Completed"
                                                                onclick="return confirm('Are you sure you want to mark this exchange as completed?')">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4 px-2">
                            {{ $exchangeRequests->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
