<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Conversation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\ConversationService;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    use AuthorizesRequests;

    public function __construct(ConversationService $service)
    {
        $this->service = $service;
    }

    public function direct(User $user)
    {
        $conversation = $this->service->findOrCreateDirectConversation($user);

        return response()->json([
            'conversation_id' => $conversation->id,
        ]);
    }

    public function messages(Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        $messages = $this->service->getMessages($conversation);

        return response()->json($messages);
    }

    public function recent()
    {
        return response()->json($this->service->getRecentConversations());
    }

    public function show(Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        return response()->json(
            $this->service->getConversationParticipants($conversation)
        );
    }

    public function markAsRead(Conversation $conversation)
    {
        $conversation->users()->updateExistingPivot(auth()->id(), [
            'last_read_at' => now(),
        ]);

        return response()->json(['status' => 'read']);
    }
}
