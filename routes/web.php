<?php

use App\Http\Controllers\admin\AccountSettingController;
use App\Http\Controllers\admin\AiEmotionDatasetController;
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
use Illuminate\Support\Facades\Route;


Route::get('auth/login', [AuthController::class, 'index'])->name('auth.login');
Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login.store');
Route::post('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');


Route::middleware('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::resource('books', BookController::class);
    Route::resource('genres', GenreController::class);
    Route::resource('characters', CharacterController::class);
    Route::resource('payment-methods', PaymentMethodController::class);

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

    Route::prefix('ai')->name('ai.')->group(function () {
        Route::resource('emotion-datasets', AiEmotionDatasetController::class);

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
