<?php

use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('V1')->group(function () {
    Route::post('users/register', [UserController::class, 'store']);
    Route::post('users/login', [UserController::class, 'login']);

    Route::middleware('auth:sanctum', 'abilities:role:user')->group(function () {
        Route::post('users/logout', [UserController::class, 'logout']);
    });
});
