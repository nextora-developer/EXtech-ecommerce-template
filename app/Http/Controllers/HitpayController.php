<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
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
            'payment_methods'  => ['card', 'paynow'],
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
        // 这里只做前端提示，实际订单状态以 Webhook 为准
        return view('payments.hitpay-return'); // 你可以先简单做一个「谢谢，付款处理中」页面
    }

    /**
     * HitPay Webhook 接收端
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->all();

        $hmac = $payload['hmac'] ?? null;
        unset($payload['hmac']);

        $calculated = hash_hmac(
            'sha256',
            http_build_query($payload),
            config('services.hitpay.salt')
        );

        if (! $hmac || ! hash_equals($calculated, $hmac)) {
            Log::warning('HitPay webhook invalid signature', [
                'hmac'       => $hmac,
                'calculated' => $calculated,
                'payload'    => $payload,
            ]);
            return response('Invalid signature', 400);
        }

        $reference = $payload['reference_number'] ?? null;
        if (! $reference) {
            return response('No reference', 400);
        }

        /** @var Order|null $order */
        $order = Order::where('order_no', $reference)->first();

        if (! $order) {
            Log::warning('HitPay webhook: order not found', ['reference' => $reference]);
            return response('Order not found', 404);
        }

        $status = $payload['status'] ?? null; // succeeded / failed / pending

        if ($status === 'succeeded') {
            $order->update([
                'status' => 'paid', // 你的订单 status 从 pending → paid
            ]);
        } elseif ($status === 'failed') {
            $order->update([
                'status' => 'pending', // 或者 failed，看你系统定义
            ]);
        }

        Log::info('HitPay webhook processed', [
            'order_id' => $order->id,
            'status'   => $status,
        ]);

        return response('OK');
    }
}
