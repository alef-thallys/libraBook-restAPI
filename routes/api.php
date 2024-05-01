<?php

use App\Http\Controllers\Api\V1\BookController;
use App\Http\Controllers\Api\V1\BookingController;
use App\Http\Controllers\Api\V1\FineController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('V1')->group(function () {
    Route::post('users/register', [UserController::class, 'store']);
    Route::post('users/login', [UserController::class, 'login']);

    Route::middleware(['auth:sanctum', 'abilities:role:user'])->group(function () {
        // Users endpoints
        Route::get('users/profile', [UserController::class, 'show']);
        Route::put('users/profile', [UserController::class, 'update']);
        Route::post('users/logout', [UserController::class, 'logout']);
        // Books endpoints
        Route::get('books', [BookController::class, 'index']);
        Route::get('books/{id}', [BookController::class, 'show']);
        // Bookings endpoints
        Route::post('books/{id}/bookings', [BookingController::class, 'store']);
        Route::get('bookings', [BookingController::class, 'index']);
        Route::get('bookings/{id}', [BookingController::class, 'show']);
        Route::put('bookings/{id}/cancel', [BookingController::class, 'cancel']);
        // Fines endpoints
        Route::get('fines', [FineController::class, 'index']);
        Route::get('fines/{id}', [FineController::class, 'show']);
        Route::post('fines/pay/all', [FineController::class, 'payAll']);
        Route::post('fines/{id}/pay', [FineController::class, 'pay']);
    });
});
