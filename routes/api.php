<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\AuthController;
use App\Http\Controllers\user\BookController;
use App\Http\Controllers\user\CartController;
use App\Http\Controllers\user\DeliveryAddressController;
use App\Http\Controllers\user\DeliveryMethodController;
use App\Http\Controllers\user\GenreController;
use App\Http\Controllers\user\PaymentMethodController;
use App\Http\Controllers\user\WishlistController;
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
    Route::get('delivery-methods', [DeliveryMethodController::class, 'index']);
    
    Route::get('delivery-addresses', [DeliveryAddressController::class, 'index']);
    Route::post('delivery-addresses', [DeliveryAddressController::class, 'store']);
    Route::get('delivery-addresses/{id}', [DeliveryAddressController::class, 'show']);
    Route::put('delivery-addresses/{id}', [DeliveryAddressController::class, 'update']);
    Route::delete('delivery-addresses/{id}', [DeliveryAddressController::class, 'destroy']);

    Route::get('wishlist', [WishlistController::class, 'index']);
    Route::post('wishlist', [WishlistController::class, 'store']);
    Route::delete('wishlist/{id}', [WishlistController::class, 'destroy']);
    Route::get('cart', [CartController::class, 'index']);
    Route::post('cart', [CartController::class, 'store']);
    Route::post('/cart/minus', [CartController::class, 'minus']);
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);
});

Route::get('genre', [GenreController::class, 'index']);
Route::get('books', [BookController::class, 'index']);
Route::get('books/{id}', [BookController::class, 'show']);
Route::get('books/bestsellers', [BookController::class, 'bestsellers']);
