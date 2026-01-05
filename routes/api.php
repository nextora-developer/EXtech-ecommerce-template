<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HitpayController;

// 测试用，看 API 路由有没有开
Route::get('/ping', function () {
    return response()->json(['status' => 'ok']);
});

// HitPay Webhook
Route::post('/hitpay/webhook', [HitpayController::class, 'handleWebhook'])
    ->name('hitpay.webhook');
