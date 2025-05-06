@extends('layouts.app')

@section('title', 'Conversation')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-4">
            <a href="{{ route('messaging.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Conversations
            </a>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
            <p>{{ session('success') }}</p>
        </div>
        @endif

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="border-b border-gray-200 bg-gray-50 px-4 py-3 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Conversation with {{ $conversation->display_name }}
                </h3>
            </div>

            <div class="px-4 py-5 sm:p-6 h-96 overflow-y-auto flex flex-col space-y-4" id="message-container">
                @if($conversation->messages->isEmpty())
                    <div class="text-center py-10">
                        <p class="text-gray-500">No messages yet. Start the conversation!</p>
                    </div>
                @else
                    @foreach($conversation->messages as $message)
                        <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-md {{ $message->sender_id === auth()->id() ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} rounded-lg px-4 py-2 shadow-sm">
                                <p class="text-sm">{{ $message->content }}</p>
                                <p class="text-xs text-gray-500 mt-1 text-right">
                                    {{ $message->created_at->format('M d, g:i a') }}
                                    @if($message->sender_id === auth()->id())
                                        @if($message->read_at)
                                            <span class="ml-1 text-gray-400">• Read</span>
                                        @else
                                            <span class="ml-1 text-gray-400">• Sent</span>
                                        @endif
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="border-t border-gray-200 px-4 py-4 sm:px-6">
                <form action="{{ route('messaging.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                    <div class="flex items-center">
                        <div class="flex-grow">
                            <textarea 
                                name="content" 
                                rows="1" 
                                placeholder="Type your message here..." 
                                class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                required
                            ></textarea>
                        </div>
                        <div class="ml-3">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                Send
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Scroll to bottom of message container on page load
        const messageContainer = document.getElementById('message-container');
        messageContainer.scrollTop = messageContainer.scrollHeight;
    });
</script>
@endsection

