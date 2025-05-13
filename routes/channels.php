<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

// Broadcast::routes(['middleware' => ['web', 'auth:sanctum']]);

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    return $user->conversations()->where('id', $conversationId)->exists();
});


Route::post('/broadcasting/auth', function (Request $request) {
    return Broadcast::auth($request);
})->middleware('auth:sanctum');