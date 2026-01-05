<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Mail\OrderPlacedMail;
use App\Mail\AdminOrderNotificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HitpayController extends Controller
{
    /**
     * 创建 HitPay Payment Request，然后 redirect 去 HitPay 付款页
     */
    public function createPayment(Order $order)
    {
        // Sandbox 必须用 SGD
        $amount   = number_format($order->total, 2, '.', '');
        $currency = 'SGD';

        $payload = [
            'amount'           => $amount,
            'currency'         => $currency,
            'reference_number' => $order->order_no,
            'name'             => $order->customer_name ?? 'Customer',
            'email'            => $order->customer_email ?? null,
            'purpose'          => 'Order ' . $order->order_no,
            'redirect_url'     => route('hitpay.return'),
            'webhook'          => route('hitpay.webhook'),
            'payment_methods'  => ['card'],
        ];

        // 生成签名
        $payload['signature'] = hash_hmac(
            'sha256',
            http_build_query($payload),
            config('services.hitpay.salt')
        );

        // 调 HitPay API
        $response = Http::withHeaders([
            'X-BUSINESS-API-KEY' => config('services.hitpay.key'),
            'accept'             => 'application/json',
        ])->post(config('services.hitpay.url') . '/v1/payment-requests', $payload);

        if (! $response->successful()) {
            Log::error('HitPay create payment failed', [
                'order_no' => $order->order_no,
                'body'     => $response->body(),
            ]);

            return redirect()
                ->route('account.orders.show', $order)
                ->with('error', 'Unable to create HitPay payment. Please try again.');
        }

        $data = $response->json();
        $checkoutUrl = $data['payment_url'] ?? $data['url'] ?? null;

        if (! $checkoutUrl) {
            Log::error('HitPay response missing checkout URL', $data);

            return redirect()
                ->route('account.orders.show', $order)
                ->with('error', 'HitPay response invalid. Please contact support.');
        }

        // 可以视情况把 HitPay 的 id 存进去（如果你以后要用）
        // $order->update([
        //     'payment_reference' => $data['id'] ?? null,
        // ]);

        return redirect()->away($checkoutUrl);
    }

    /**
     * 用户付款后浏览器跳回来的页面（redirect_url）
     */
    public function handleReturn(Request $request)
    {
        $reference = $request->query('reference');

        // 如果拿到 reference，就尽量带用户去那一张订单
        if ($reference) {
            $order = Order::where('order_no', $reference)->first();

            if ($order) {
                return redirect()
                    ->route('account.orders.show', $order)
                    ->with('success', 'We have received your payment result. If the status is still pending, it will update shortly.');
            }
        }

        // 找不到就回订单列表
        return redirect()
            ->route('account.orders.index')
            ->with('success', 'We have received your payment result. Please check your orders.');
    }


    /**
     * HitPay Webhook 接收端
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->all();
        \Log::info('HitPay webhook received', $payload);

        // 1️⃣ 验证 HMAC 签名
        $receivedHmac = $payload['hmac'] ?? null;

        if (! $receivedHmac) {
            \Log::warning('HitPay webhook missing hmac', $payload);
            return response('Missing hmac', 400);
        }

        // 计算签名前，要先移除 hmac 本身
        unset($payload['hmac']);

        $calculated = hash_hmac(
            'sha256',
            http_build_query($payload),
            config('services.hitpay.salt')   // 确认跟 HitPay 后台的一样
        );

        if (! hash_equals($calculated, $receivedHmac)) {
            \Log::warning('HitPay webhook invalid signature', [
                'payload'    => $payload,
                'calculated' => $calculated,
                'received'   => $receivedHmac,
            ]);

            return response('Invalid signature', 400);
        }

        // 2️⃣ 用 reference_number 找订单
        $reference = $payload['reference_number'] ?? null;

        if (! $reference) {
            \Log::warning('HitPay webhook missing reference_number', $payload);
            return response('Missing reference_number', 400);
        }

        /** @var \App\Models\Order|null $order */
        $order = Order::where('order_no', $reference)->first();

        if (! $order) {
            \Log::warning('HitPay webhook order not found', ['reference' => $reference]);
            return response('Order not found', 404);
        }

        $oldStatus = $order->status;
        $status    = $payload['status'] ?? null;   // completed / succeeded / failed / pending...

        \Log::info('HitPay webhook order status', [
            'order_no'      => $order->order_no,
            'hitpay_status' => $status,
            'old_status'    => $oldStatus,
        ]);

        // 3️⃣ 根据 HitPay 返回状态更新订单
        if (in_array($status, ['succeeded', 'completed'], true)) {
            $order->update([
                'status'         => 'paid',
                'payment_status' => $status,
            ]);

            // 4️⃣ 只有第一次从 pending → paid 才发邮件，避免重复
            if ($oldStatus !== 'paid') {
                try {
                    if ($order->customer_email) {
                        Mail::to($order->customer_email)->send(new OrderPlacedMail($order));
                    }

                    if (config('mail.admin_address')) {
                        Mail::to(config('mail.admin_address'))->send(new AdminOrderNotificationMail($order));
                    }

                    \Log::info('HitPay webhook emails sent for order ' . $order->order_no);
                } catch (\Throwable $e) {
                    \Log::error('HitPay webhook email failed for ' . $order->order_no . ' : ' . $e->getMessage());
                }
            }
        } elseif ($status === 'failed') {
            $order->update([
                'payment_status' => 'failed',
            ]);
        } else {
            // 其他状态，先只记录
            \Log::info('HitPay webhook unhandled status', [
                'order_no' => $order->order_no,
                'status'   => $status,
            ]);
        }

        return response('OK');
    }
}
