<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // ⚠️ HitPay Webhook —— 必须允许外部系统访问
        'payment/hitpay/webhook',

        // （如果以后还有其他支付回调，也可以放在这里）
        // 'payment/xxxxx/webhook',
    ];
}
