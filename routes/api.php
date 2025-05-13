<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/conversations/direct/{user}', [ConversationController::class, 'direct']);
    Route::get('/conversations/{conversation}/messages', [ConversationController::class, 'messages']);
    Route::post('/conversations/{conversation}/messages', [MessageController::class, 'store']);

    Route::get('/user', [UserController::class, 'me']);
    Route::put('/user', [UserController::class, 'updateProfile']);
    Route::put('/user/password', [UserController::class, 'updatePassword']);
    Route::post('/user/photo', [UserController::class, 'updatePhoto']);
    
    Route::get('/conversations/recent', [ConversationController::class, 'recent']);
    Route::get('/conversations/{conversation}', [ConversationController::class, 'show']);
    Route::post('/conversations/{conversation}/read', [ConversationController::class, 'markAsRead']);

});
