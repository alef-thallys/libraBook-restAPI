<?php

use App\Http\Controllers\Api\V1\Admin\AdminBookController;
use App\Http\Controllers\Api\V1\Admin\AdminBookingController;
use App\Http\Controllers\Api\V1\Admin\AdminUserController;
use App\Http\Controllers\Api\V1\BookController;
use App\Http\Controllers\Api\V1\BookingController;
use App\Http\Controllers\Api\V1\FineController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('V1')->group(function () {
    Route::post('users/register', [UserController::class, 'store']);
    Route::post('users/login', [UserController::class, 'login']);

    Route::middleware(['auth:sanctum'])->group(function () {

        Route::middleware(['abilities:role:user'])->group(function () {
            // Users endpoints
            Route::get('users/profile', [UserController::class, 'show']);
            Route::put('users/profile', [UserController::class, 'update']);
            Route::post('users/logout', [UserController::class, 'logout']);

            // Books endpoints
            Route::get('books', [BookController::class, 'index']);
            Route::get('books/{id}', [BookController::class, 'show']);

            // Bookings endpoints
            Route::get('bookings', [BookingController::class, 'index']);
            Route::get('bookings/{id}', [BookingController::class, 'show']);
            Route::post('books/{id}/bookings', [BookingController::class, 'store']);;

            // Fines endpoints
            Route::get('fines', [FineController::class, 'index']);
            Route::get('fines/{id}', [FineController::class, 'show']);
            Route::post('fines/pay/all', [FineController::class, 'payAll']);
            Route::post('fines/{id}/pay', [FineController::class, 'pay']);
        });

        Route::middleware(['abilities:role:admin'])->prefix('admin')->group(function () {
            // Users endpoints
            Route::get('users', [AdminUserController::class, 'index']);
            Route::get('users/{id}', [AdminUserController::class, 'show']);
            Route::delete('users/{id}', [AdminUserController::class, 'destroy']);

            // Book endpoints
            Route::get('books', [AdminBookController::class, 'index']);
            Route::get('books/{id}', [AdminBookController::class, 'show']);
            Route::post('books', [AdminBookController::class, 'store']);
            Route::put('books/{id}', [AdminBookController::class, 'update']);
            Route::delete('books/{id}', [AdminBookController::class, 'destroy']);

            // Bookings endpoints
            Route::get('bookings', [AdminBookingController::class, 'index']);
            Route::get('bookings/{id}', [AdminBookingController::class, 'show']);
            Route::get('bookings/user/{id}', [AdminBookingController::class, 'showByUser']);
            Route::put('bookings/{id}/return', [AdminBookingController::class, 'return']);
        });
    });
});
