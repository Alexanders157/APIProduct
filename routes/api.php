<?php

use App\Http\Controllers\MovieController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function() {
    Route::post('logout', [UserController::class, 'logout']);

    Route::get('movies', [MovieController::class, 'index']);

    Route::post('tickets', [TicketController::class, 'store']);

    Route::get('sessions/{session}', [SessionController::class, 'show']);
});

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
