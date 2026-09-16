<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/payment/webhook', [\App\Http\Controllers\Api\WebhookController::class, 'handle']);
Route::match(['get', 'post'], '/callback/gopay-notif', [\App\Http\Controllers\Api\GoPayWebhookController::class, 'handle']);

