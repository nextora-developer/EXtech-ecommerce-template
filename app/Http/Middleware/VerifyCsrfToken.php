<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // 这里写的是「路径」，不要写开头的 /
        'payment/hitpay/webhook',
        // 如果以后你有其它 webhook，可以继续加在这里
        // 'stripe/*',
        // 'payment/ipn/*',
    ];
}
