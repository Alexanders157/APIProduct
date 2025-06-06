<?php

use App\Http\Controllers\Admin\AdminHallController;
use App\Http\Controllers\Admin\AdminMovieController;
use App\Http\Controllers\Admin\AdminSessionController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::post('/movies', [AdminMovieController::class, 'store']);
    Route::put('/movies/{id}', [AdminMovieController::class, 'update']);
    Route::delete('/movies/{id}', [AdminMovieController::class, 'destroy']);

    Route::post('/halls', [AdminHallController::class, 'store']);

    Route::post('/sessions', [AdminSessionController::class, 'store']);
    Route::delete('/sessions/{id}', [AdminSessionController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function() {
    Route::post('logout', [UserController::class, 'logout']);

    Route::get('movies', [MovieController::class, 'index']);
    Route::get('movies/{movie}', [MovieController::class, 'show']);

    Route::post('tickets', [TicketController::class, 'store']);
    Route::get('user/tickets/{ticket}', [TicketController::class, 'show']);
    Route::get('user/tickets', [TicketController::class, 'index']);

    Route::get('sessions', [SessionController::class, 'index']);
    Route::get('sessions/{session}', [SessionController::class, 'show']);

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return response()->json(['message' => 'Email успешно подтвержден'], 200);
    })->middleware(['signed'])->name('verification.verify');
});

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
