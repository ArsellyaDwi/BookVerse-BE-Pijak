<?php

use App\Http\Controllers\admin\AccountSettingController;
use App\Http\Controllers\admin\AiEmotionDatasetController;
use App\Http\Controllers\admin\AIEmotionRuleController;
use App\Http\Controllers\admin\AiRecommendationLogController;
use App\Http\Controllers\admin\AiTrainingLogController;
use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\admin\BookController;
use App\Http\Controllers\admin\CharacterController;
use App\Http\Controllers\admin\CustomerController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\DeliveryMethodController;
use App\Http\Controllers\admin\GenreController;
use App\Http\Controllers\admin\PaymentMethodController;
use App\Http\Controllers\admin\StoreSettingController;
use App\Http\Controllers\admin\TransactionController;
use App\Http\Controllers\API\ForgotPasswordController as APIForgotPasswordController;
use App\Http\Controllers\user\ForgotPasswordController;
use App\Http\Controllers\user\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\ContactMessageController;
use App\Http\Controllers\API\ContactController as APIContactController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\User\PageController;
use App\Http\Controllers\Api\SubscribeController;

// Guest routes (no authentication required)
Route::get('auth/login', [AuthController::class, 'index'])->name('auth.login');
Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login.store');
Route::post('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::get('/terms', [PageController::class, 'terms'])->name('pages.terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('pages.privacy');
Route::get('/faq', [PageController::class, 'faq'])->name('pages.faq');
Route::get('/return-policy', [PageController::class, 'returnPolicy'])->name('pages.return');

// Admin routes (protected)
Route::middleware('admin')->name('admin.')->group(function () {

    Route::get('/', [DashboardController::class, 'index']);


    Route::resource('books', BookController::class);
    Route::resource('genres', GenreController::class);
    Route::resource('characters', CharacterController::class);
    Route::resource('payment-methods', PaymentMethodController::class);

    Route::post('books/update-all-mood-tags', [BookController::class, 'updateAllMoodTags'])->name('books.update-all-mood-tags');
    Route::post('books/{id}/update-mood-tags', [BookController::class, 'updateSingleBookMoodTags'])->name('books.update-single-mood-tags');

    Route::get('books/import/form', [BookController::class, 'showImportForm'])->name('books.import.form');
    Route::post('books/import', [BookController::class, 'import'])->name('books.import');
    Route::get('books/download-template', [BookController::class, 'downloadTemplate'])->name('books.download-template');

    Route::resource('transactions', TransactionController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
    Route::get('transactions/{transaction}/invoice', [TransactionController::class, 'invoice'])->name('transactions.invoice');
    Route::patch('transactions/{transaction}/status', [TransactionController::class, 'updateStatus'])->name('transactions.update-status');

    Route::resource('customers', CustomerController::class);
    Route::get('settings', [StoreSettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [StoreSettingController::class, 'update'])->name('settings.update');

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('delivery-methods', DeliveryMethodController::class);
    Route::patch('delivery-methods/{deliveryMethod}/toggle-status', [DeliveryMethodController::class, 'toggleStatus'])->name('delivery-methods.toggle-status');

    Route::get('account-settings', [AccountSettingController::class, 'index'])->name('account-settings.index');
    Route::put('account-settings', [AccountSettingController::class, 'update'])->name('account-settings.update');
    Route::get('contact-messages', [ContactMessageController::class, 'index'])->name('contact-messages.index');
    Route::get('contact-messages/{id}', [ContactMessageController::class, 'show'])->name('contact-messages.show');
    Route::post('contact-messages/{id}/reply', [ContactMessageController::class, 'reply'])->name('contact-messages.reply');
    Route::delete('contact-messages/{id}', [ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');
    Route::post('contact-messages/bulk-delete', [ContactMessageController::class, 'bulkDelete'])->name('contact-messages.bulk-delete');
    Route::post('contact-messages/{id}/resend-email', [ContactMessageController::class, 'resendEmail'])
        ->name('admin.contact-messages.resend-email');
    Route::prefix('ai')->name('ai.')->group(function () {
        Route::resource('emotion-datasets', AiEmotionDatasetController::class);

        Route::get('emotion-rules', [AIEmotionRuleController::class, 'index'])->name('emotion-rules.index');
        Route::post('emotion-rules', [AIEmotionRuleController::class, 'storeOrUpdate'])->name('emotion-rules.store-or-update');
        Route::post('/bulk', [AIEmotionRuleController::class, 'bulkUpdate'])->name('emotion-rules.bulk-update');

        Route::get('emotion-datasets/import/form', [AiEmotionDatasetController::class, 'showImportForm'])->name('emotion-datasets.import.form');
        Route::post('emotion-datasets/import', [AiEmotionDatasetController::class, 'import'])->name('emotion-datasets.import');
        Route::get('emotion-datasets/download-template', [AiEmotionDatasetController::class, 'downloadTemplate'])->name('emotion-datasets.download-template');
        Route::post('emotion-datasets/bulk-delete', [AiEmotionDatasetController::class, 'bulkDelete'])->name('emotion-datasets.bulk-delete');

        Route::resource('training-logs', AiTrainingLogController::class)->only(['index', 'destroy']);
        Route::post('training-logs/retrain', [AiTrainingLogController::class, 'retrain'])->name('training-logs.retrain');
        Route::delete('training-logs/clear-all', [AiTrainingLogController::class, 'clearAll'])->name('training-logs.clear-all');

        Route::resource('recommendation-logs', AiRecommendationLogController::class)->only(['index', 'show', 'destroy']);
        Route::delete('recommendation-logs/clear-all', [AiRecommendationLogController::class, 'clearAll'])->name('recommendation-logs.clear-all');
        Route::get('recommendation-logs/export', [AiRecommendationLogController::class, 'export'])->name('recommendation-logs.export');
    });
});
