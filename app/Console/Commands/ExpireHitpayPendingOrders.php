<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class ExpireHitpayPendingOrders extends Command
{
    // 这个就是以后在 scheduler 里 call 的名字
    protected $signature = 'orders:expire-hitpay-pending';

    protected $description = 'Mark old pending HitPay orders as failed';

    public function handle(): void
    {
        $cutoff = now()->subMinutes(60); // 超过 60 分钟还没有 payment_status 的 HitPay 订单

        $affected = Order::where('status', 'pending')
            ->where('payment_method_code', 'hitpay')   // ✅ 只处理 HitPay
            ->whereNull('payment_status')             // ✅ 没收到成功/失败结果
            ->where('created_at', '<', $cutoff)
            ->update(['status' => 'failed']);

        $this->info("Expired {$affected} HitPay pending orders.");
    }
}
