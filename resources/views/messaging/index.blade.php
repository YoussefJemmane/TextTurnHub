@extends('layouts.app')

@section('title', 'My Conversations')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">My Conversations</h1>
            <p class="text-gray-600">Manage your messages with suppliers and buyers</p>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
            <p>{{ session('success') }}</p>
        </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            @if($conversations->isEmpty())
                <div class="p-12 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">No conversations yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Start a conversation by contacting a supplier or requesting a textile waste exchange.</p>
                </div>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($conversations as $conversation)
                        <li class="hover:bg-gray-50">
                            <a href="{{ route('messaging.show', $conversation) }}" class="block">
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $conversation->display_name }}
                                                </p>
                                                <p class="text-sm text-gray-500 truncate">
                                                    @if($conversation->latestMessage())
                                                        {{ Str::limit($conversation->latestMessage()->content, 50) }}
                                                    @else
                                                        <span class="italic">No messages</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex flex-col items-end">
                                            <p class="text-xs text-gray-500">
                                                @if($conversation->latestMessage())
                                                    {{ $conversation->latestMessage()->created_at->diffForHumans() }}
                                                @endif
                                            </p>
                                            @if($conversation->unread_count > 0)
                                                <span class="inline-flex items-center mt-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ $conversation->unread_count }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection

