<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MessagingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Display a listing of the user's conversations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {

        $conversations = Auth::user()->conversations()
            ->with(['users' => function ($query) {
                $query->where('users.id', '!=', Auth::id());
            }])
            ->withCount(['messages as unread_count' => function ($query) {
                $query->whereNull('read_at')
                    ->where('sender_id', '!=', Auth::id());
            }])
            ->get();
        return view('messaging.index', compact('conversations'));
    }

    /**
     * Display the specified conversation.
     *
     * @param  \App\Models\Conversation  $conversation
     * @return \Illuminate\View\View
     */
    public function show(Conversation $conversation)
    {
        // Check if the user is part of this conversation
        if (!$conversation->users->contains(Auth::id())) {
            abort(403, 'You do not have access to this conversation.');
        }

        // Mark all unread messages as read
        $conversation->messages()
            ->whereNull('read_at')
            ->where('sender_id', '!=', Auth::id())
            ->update(['read_at' => now()]);

        // Get the conversation with its messages and users
        $conversation->load(['messages.sender', 'users' => function ($query) {
            $query->where('users.id', '!=', Auth::id());
        }]);

        return view('messaging.show', compact('conversation'));
    }

    /**
     * Store a newly created message in the specified conversation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'conversation_id' => ['required', 'exists:conversations,id'],
            'content' => ['required', 'string'],
        ]);

        $conversation = Conversation::findOrFail($validated['conversation_id']);

        // Check if the user is part of this conversation
        if (!$conversation->users->contains(Auth::id())) {
            abort(403, 'You do not have access to this conversation.');
        }

        // Create the message
        $message = new Message([
            'content' => $validated['content'],
            'sender_id' => Auth::id(),
        ]);

        $conversation->messages()->save($message);

        return back()->with('success', 'Message sent successfully.');
    }

    /**
     * Create a new conversation with another user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createConversation(Request $request)
    {

        $validated = $request->validate([
            'recipient_id' => [
                'required',
                'exists:users,id',
                Rule::notIn([Auth::id()])
            ],
            'message' => ['required', 'string'],
        ]);

        $otherUser = User::findOrFail($validated['recipient_id']);

        // Check if a conversation already exists between these users
        $existingConversation = Auth::user()->conversations()
            ->whereHas('users', function ($query) use ($otherUser) {
                $query->where('users.id', $otherUser->id);
            })
            ->first();

        if ($existingConversation) {
            // If a conversation exists, just add a new message to it
            $message = new Message([
                'content' => $validated['message'],
                'sender_id' => Auth::id(),
            ]);

            $existingConversation->messages()->save($message);

            return redirect()->route('messaging.show', $existingConversation)
                ->with('success', 'Message sent successfully.');
        }

        // Create a new conversation
        $conversation = new Conversation();
        $conversation->save();

        // Attach both users to the conversation
        $conversation->users()->attach([Auth::id(), $otherUser->id]);

        // Create the first message
        $message = new Message([
            'content' => $validated['message'],
            'sender_id' => Auth::id(),
        ]);

        $conversation->messages()->save($message);

        return redirect()->route('messaging.show', $conversation)
            ->with('success', 'Conversation started successfully.');
    }
}

