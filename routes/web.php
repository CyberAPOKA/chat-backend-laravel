<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

require __DIR__ . '/auth.php';

Route::get('/session-test', function (Request $request) {
    return [
        'user' => $request->user(),
        'session' => $request->session()->all()
    ];
});
