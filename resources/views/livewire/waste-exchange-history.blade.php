<div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 mb-8">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-700 mb-4">Filter Options</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select wire:model.live="status" id="status"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-600 focus:border-green-600 sm:text-sm rounded-lg">
                        <option value="">All</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <div>
                    <label for="dateFrom" class="block text-sm font-medium text-gray-700">From Date</label>
                    <input type="date" wire:model.live="dateFrom" id="dateFrom"
                        class="mt-1 focus:ring-green-600 focus:border-green-600 block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg">
                </div>

                <div>
                    <label for="dateTo" class="block text-sm font-medium text-gray-700">To Date</label>
                    <input type="date" wire:model.live="dateTo" id="dateTo"
                        class="mt-1 focus:ring-green-600 focus:border-green-600 block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg">
                </div>

                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="search" wire:model.live.debounce.100ms="search" id="search"
                            class="focus:ring-green-600 focus:border-green-600 block w-full pl-10 sm:text-sm border-gray-300 rounded-lg"
                            placeholder="Search exchanges...">
                    </div>
                </div>
            </div>

            <div class="mt-4 flex justify-end space-x-3">
                <button wire:click="resetFilters"
                    class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Reset Filters
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">


        <!-- Exchange History List -->
        @if ($exchanges->isEmpty())
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No exchange history</h3>
                <p class="mt-1 text-sm text-gray-500">You don't have any completed or cancelled exchanges yet.</p>
            </div>
        @else
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Waste Item
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Exchange Partners
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Quantity & Price
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
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
                        @foreach ($exchanges as $exchange)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if (!empty($exchange->textileWaste->images) && is_array(json_decode($exchange->textileWaste->images)))
                                                <img class="h-10 w-10 rounded-full object-cover"
                                                    src="{{ Storage::url(json_decode($exchange->textileWaste->images)[0]) }}"
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
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $exchange->textileWaste->title }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ ucfirst($exchange->textileWaste->waste_type) }} -
                                                {{ $exchange->textileWaste->material_type }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <span class="font-medium">Supplier:</span>
                                        {{ $exchange->supplierCompany->company_name }}
                                    </div>
                                    <div class="text-sm text-gray-900 mt-1">
                                        <span class="font-medium">Requester:</span>
                                        {{ $exchange->receiverCompany->company_name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ number_format($exchange->quantity, 2) }}
                                        {{ $exchange->textileWaste->unit }}</div>
                                    @if ($exchange->final_price)
                                        <div class="text-sm text-gray-500">
                                            ${{ number_format($exchange->final_price, 2) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($exchange->status === 'completed' && $exchange->exchange_date)
                                        <div class="text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($exchange->exchange_date)->format('M d, Y') }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($exchange->exchange_date)->format('h:i A') }}
                                        </div>
                                    @else
                                        <div class="text-sm text-gray-900">
                                            {{ $exchange->updated_at->format('M d, Y') }}</div>
                                        <div class="text-sm text-gray-500">
                                            {{ $exchange->updated_at->format('h:i A') }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($exchange->status === 'completed')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Completed
                                        </span>
                                    @elseif($exchange->status === 'cancelled')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Cancelled
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('waste-exchanges.show', $exchange) }}"
                                        class="text-blue-600 hover:text-blue-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $exchanges->links() }}
            </div>
        @endif
    </div>
</div>
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('reset-filters', () => {
            document.getElementById('status').value = '';
            document.getElementById('dateFrom').value = '';
            document.getElementById('dateTo').value = '';
            document.getElementById('search').value = '';
        });
    });
</script>
