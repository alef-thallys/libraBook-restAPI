<?php

use App\Http\Controllers\Api\V1\Admin\AdminBookController;
use App\Http\Controllers\Api\V1\Admin\AdminBookingController;
use App\Http\Controllers\Api\V1\Admin\AdminFineController;
use App\Http\Controllers\Api\V1\Admin\AdminStockController;
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
            Route::get('books/{title?}', [BookController::class, 'index'])->where('title', '[a-zA-Z]+');
            Route::get('books/{id}', [BookController::class, 'show'])->where('id', '[0-9]+');

            // Bookings endpoints
            Route::get('booking', [BookingController::class, 'index']);
            Route::post('books/{id}/booking', [BookingController::class, 'store'])->where('id', '[0-9]+');
            Route::put('booking/return', [BookingController::class, 'return']);

            // Fines endpoints
            Route::get('fine', [FineController::class, 'index']);
            Route::post('fine/pay', [FineController::class, 'pay']);
        });

        // Route::middleware(['abilities:role:admin'])->prefix('admin')->group(function () {
        //     // Users endpoints
        //     Route::get('users', [AdminUserController::class, 'index']);
        //     Route::get('users/{id}', [AdminUserController::class, 'show']);
        //     Route::delete('users/{id}', [AdminUserController::class, 'destroy']);

        //     // Book endpoints       
        //     Route::get('books/{title?}', [AdminBookController::class, 'index']);
        //     Route::get('books/{id}', [AdminBookController::class, 'show']);
        //     Route::post('books', [AdminBookController::class, 'store']);
        //     Route::put('books/{id}', [AdminBookController::class, 'update']);
        //     Route::delete('books/{id}', [AdminBookController::class, 'destroy']);

        //     // Bookings endpoints
        //     Route::get('bookings/{user_id?}', [AdminBookingController::class, 'index']);
        //     Route::get('bookings/{id}', [AdminBookingController::class, 'show']);

        //     // Fines endpoints
        //     Route::get('fines/{user_id?}', [AdminFineController::class, 'index']);
        //     Route::get('fines/{id}', [AdminFineController::class, 'show']);

        //     // Stock endpoints
        //     Route::get('stock', [AdminStockController::class, 'index']);
        //     Route::get('stock/{id}', [AdminStockController::class, 'show']);
        //     Route::put('stock/{id}', [AdminStockController::class, 'update']);
        // });
    });
});
