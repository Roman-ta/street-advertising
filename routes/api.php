<?php

use Illuminate\Support\Facades\Route;

// Публичные
Route::prefix('v1/public')->group(function () {
    Route::get('/spots', [SpotController::class, 'index']);        // карта
    Route::get('/spots/{spot}', [SpotController::class, 'show']); // страница
    Route::get('/spots/{spot}/availability', [SpotController::class, 'availability']);
});

// Клиент
Route::prefix('v1/client')->middleware(['livewire:sanctum', 'role:client', 'legal_signed'])->group(function () {
    Route::get('/orders', [ClientOrderController::class, 'index']);
    Route::post('/orders', [ClientOrderController::class, 'store']);
    Route::post('/cart', [CartController::class, 'add']);
});

// Партнёр
Route::prefix('v1/partner')->middleware(['livewire:sanctum', 'role:partner', 'legal_signed', 'profile_complete'])->group(function () {
    Route::apiResource('/spots', PartnerSpotController::class);
    Route::post('/orders/{order}/photo-report', [PartnerOrderController::class, 'uploadPhotoReport']);
});

// Админ
Route::prefix('v1/admin')->middleware(['livewire:sanctum', 'role:admin'])->group(function () {
    Route::get('/users', [AdminUserController::class, 'index']);
    Route::get('/orders', [AdminOrderController::class, 'index']);
    Route::post('/spots/{spot}/moderate', [AdminSpotController::class, 'moderate']);
});

// Webhooks (без livewire — но с проверкой подписи)
Route::post('/webhook/payment', [PaymentWebhookController::class, 'handle']);
Route::post('/webhook/telegram', [TelegramWebhookController::class, 'handle']);
