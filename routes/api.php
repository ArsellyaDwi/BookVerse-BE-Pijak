<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\AuthController;
use App\Http\Controllers\user\BookController;
use App\Http\Controllers\user\CartController;
use App\Http\Controllers\user\CheckoutController;
use App\Http\Controllers\user\DeliveryAddressController;
use App\Http\Controllers\user\DeliveryMethodController;
use App\Http\Controllers\user\ForgotPasswordController;
use App\Http\Controllers\Api\GenreController;
use App\Http\Controllers\user\PaymentMethodController;
use App\Http\Controllers\user\ResetPasswordController;
use App\Http\Controllers\user\WishlistController;
use App\Http\Controllers\API\ContactController;
use App\Models\PaymentMethod;
use App\Http\Controllers\Api\SubscribeController;
use App\Http\Controllers\user\EmotionController;

Route::post('auth/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('auth/reset-password', [ResetPasswordController::class, 'reset']);

Route::post('/subscribe', [SubscribeController::class, 'subscribe']);

Route::group([
    'middleware' => ['api', 'auth:api'],
], function () {

    Route::group(['prefix' => 'auth'], function () {
        // Auth Controller
        Route::post('login', [AuthController::class, 'login'])->withoutMiddleware(['auth:api']);
        Route::post('register', [AuthController::class, 'register'])->withoutMiddleware(['auth:api']);

        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('me', [AuthController::class, 'logout']);

        Route::put('/', [AuthController::class, 'updateAccount']);
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::put('profile', [AuthController::class, 'updateProfile']);
        Route::put('change-password', [AuthController::class, 'changePassword']);

    });


    Route::get('payment-methods', [PaymentMethodController::class, 'index']);
    Route::get('delivery-methods', [DeliveryMethodController::class, 'index']);

    Route::get('delivery-addresses', [DeliveryAddressController::class, 'index']);
    Route::post('delivery-addresses', [DeliveryAddressController::class, 'store']);
    Route::put('delivery-addresses/{id}', [DeliveryAddressController::class, 'update']);
    Route::delete('delivery-addresses/{id}', [DeliveryAddressController::class, 'destroy']);
    Route::patch('delivery-addresses/{id}/default', [DeliveryAddressController::class, 'setDefault']);

    Route::get('wishlist', [WishlistController::class, 'index']);
    Route::post('wishlist', [WishlistController::class, 'store']);
    Route::delete('wishlist/{id}', [WishlistController::class, 'destroy']);
    Route::get('cart', [CartController::class, 'index']);
    Route::post('cart', [CartController::class, 'store']);
    Route::post('/cart/minus', [CartController::class, 'minus']);
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);

    Route::get('/checkout/data', [CheckoutController::class, 'getCheckoutData']);
    Route::post('/checkout', [CheckoutController::class, 'createTransaction']);
    Route::post('/transactions/{id}/upload-payment', [CheckoutController::class, 'uploadPaymentProof']);
    Route::get('/transactions', [CheckoutController::class, 'getUserTransactions']);
    Route::get('/transactions/{id}', [CheckoutController::class, 'getTransactionDetail']);
});

Route::post('/contact/send', [ContactController::class, 'send']);
Route::get('/genre', [GenreController::class, 'index']);
Route::get('/genre/{slug}', [GenreController::class, 'show']);
Route::get('/genres/{slug}/books', [GenreController::class, 'getBooksByGenre']);

Route::get('books', [BookController::class, 'index']);
Route::get('/books/init', [BookController::class, 'init']);
Route::get('books/{id}', [BookController::class, 'show']);
Route::get('books/bestsellers', [BookController::class, 'bestsellers']);

Route::post('/emotion/detect', [EmotionController::class, 'detect']);
Route::post('/emotion/recommend', [EmotionController::class, 'recommend']);
Route::post('/emotion/analyze', [EmotionController::class, 'analyzeAndRecommend']);
Route::get('/emotion/list', [EmotionController::class, 'getEmotions']);
