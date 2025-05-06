<div class="flow-root">
    <ul role="list" class="-mb-8 pb-12">
        <li>
            <div class="relative pb-8">
                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                <div class="relative flex space-x-3">
                    <div>
                        <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                    </div>
                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                        <div>
                            <p class="text-sm text-gray-500">Request Created by <span class="font-medium text-gray-900">{{ $exchange->receiverCompany->company_name }}</span></p>
                        </div>
                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                            <time datetime="{{ $exchange->created_at }}">{{ $exchange->created_at->format('M d, Y h:i A') }}</time>
                        </div>
                    </div>
                </div>
            </div>
        </li>

        @if($exchange->status !== 'requested')
        <li>
            <div class="relative pb-8">
                @if($exchange->status === 'completed')
                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                @endif
                <div class="relative flex space-x-3">
                    <div>
                        <span class="h-8 w-8 rounded-full {{ $exchange->status === 'cancelled' ? 'bg-red-500' : 'bg-green-500' }} flex items-center justify-center ring-8 ring-white">
                            @if($exchange->status === 'cancelled')
                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            @else
                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            @endif
                        </span>
                    </div>
                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                        <div>
                            <p class="text-sm text-gray-500">Request {{ $exchange->status === 'cancelled' ? 'Cancelled' : 'Accepted' }} by <span class="font-medium text-gray-900">{{ $exchange->supplierCompany->company_name }}</span></p>
                        </div>
                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                            <time datetime="{{ $exchange->updated_at }}">{{ $exchange->updated_at->format('M d, Y h:i A') }}</time>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        @endif

        @if($exchange->status === 'completed')
        <li>
            <div class="relative">
                <div class="relative flex space-x-3">
                    <div>
                        <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                    </div>
                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                        <div>
                            <p class="text-sm text-gray-500">Exchange Completed</p>
                        </div>
                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                            <time datetime="{{ $exchange->exchange_date }}">{{ \Carbon\Carbon::parse($exchange->exchange_date)->format('M d, Y h:i A') }}</time>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        @endif
    </ul>
</div>
