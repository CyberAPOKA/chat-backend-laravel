<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

// Broadcast::routes(['middleware' => ['web', 'auth:sanctum']]);

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    \Illuminate\Support\Facades\Log::info('👥 Tentando autorizar acesso ao canal', [
        'user_id' => $user->id,
        'conversation_id' => $conversationId,
    ]);

    return $user->conversations()->where('id', $conversationId)->exists();
});


Route::post('/broadcasting/auth', function (Request $request) {
    \Illuminate\Support\Facades\Log::info('🔒 Broadcasting Auth Request', $request->all());

    return Broadcast::auth($request);
})->middleware('auth:sanctum');