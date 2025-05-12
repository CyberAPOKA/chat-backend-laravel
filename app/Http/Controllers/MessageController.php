<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Services\MessageService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct(MessageService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request, Conversation $conversation)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $message = $this->service->store($conversation, $validated['content']);

        return response()->json($message);
    }
}
