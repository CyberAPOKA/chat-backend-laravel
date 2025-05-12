<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Log;

class MessageService
{
    public function store(Conversation $conversation, string $content): Message
    {
        $message = $conversation->messages()->create([
            'user_id' => Auth::id(),
            'content' => $content,
            'type' => 'text',
            'sent_at' => now(),
        ]);

        $message->load('user');

        Log::info('Message sent', [
            'teste 1' => 'testando'
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return $message;
    }
}
