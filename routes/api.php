<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\AuthController;
use App\Http\Controllers\user\BookController;
use App\Http\Controllers\user\GenreController;
use App\Http\Controllers\user\PaymentMethodController;
use App\Models\PaymentMethod;

Route::group([
    'middleware' => ['api', 'auth:api'],
], function () {

    Route::group(['prefix' => 'auth'], function () {
        // Auth Controller
        Route::post('login', [AuthController::class, 'login'])->withoutMiddleware(['auth:api']);
        Route::post('register', [AuthController::class, 'register'])->withoutMiddleware(['auth:api']);

        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('me', [AuthController::class, 'logout']);
    });

    
    Route::get('payment-methods', [PaymentMethodController::class, 'index']);
});

Route::get('genre', [GenreController::class, 'index']);
Route::get('books', [BookController::class, 'index']);
Route::get('books/{id}', [BookController::class, 'show']);
