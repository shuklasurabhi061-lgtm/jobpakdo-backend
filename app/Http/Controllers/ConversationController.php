<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    // List all conversations for the logged-in user (Employer or Seeker)
    public function index()
    {
        $userId = Auth::id();

        $conversations = Conversation::where('employer_id', $userId)
            ->orWhere('seeker_id', $userId)
            ->with(['employer', 'seeker', 'messages' => function($q) {
                $q->latest()->limit(1);
            }])
            ->latest()
            ->get();

        return response()->json(['success' => true, 'data' => $conversations]);
    }

    // Start a new conversation or fetch existing one
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
        ]);

        $userId = Auth::id();
        $receiverId = $request->receiver_id;

        // Check if conversation already exists between these two users
        $conversation = Conversation::where(function($q) use ($userId, $receiverId) {
                $q->where('employer_id', $userId)->where('seeker_id', $receiverId);
            })->orWhere(function($q) use ($userId, $receiverId) {
                $q->where('employer_id', $receiverId)->where('seeker_id', $userId);
            })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'employer_id' => $userId, // Assuming initiator or handled via roles
                'seeker_id' => $receiverId,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Conversation ready',
            'data' => $conversation
        ]);
    }

    // Get messages of a specific conversation
    public function show($id)
    {
        $conversation = Conversation::with(['messages.sender', 'employer', 'seeker'])->find($id);

        if (!$conversation) {
            return response()->json(['success' => false, 'message' => 'Conversation not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $conversation]);
    }

    // Send a message in a conversation
    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $conversation = Conversation::find($id);

        if (!$conversation) {
            return response()->json(['success' => false, 'message' => 'Conversation not found'], 404);
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => $message->load('sender')
        ], 201);
    }
}
