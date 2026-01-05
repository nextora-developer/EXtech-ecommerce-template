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
            'X-BUSINESS-API-KEY' => config('services.hitpay.api_key'),
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

        // 0️⃣ 先记 log，确认 HitPay / Postman 送了什么
        Log::info('HitPay webhook received', [
            'payload' => $payload,
            'headers' => $request->headers->all(),
            'user_agent' => $request->userAgent(),
        ]);

        /**
         * 1️⃣ 是否要跳过 HMAC（方便用 Postman 测试）
         *
         * - local 环境：直接跳过
         * - User-Agent 包含 PostmanRuntime：视为你在用 Postman 调试，也跳过
         * - 其他情况（真正 HitPay 调用）：一定要过 HMAC
         */
        $skipHmac = app()->environment('local')
            || str_contains($request->userAgent() ?? '', 'PostmanRuntime');

        if ($skipHmac) {
            Log::info('HitPay webhook: skip HMAC verification (debug mode)', [
                'env'        => app()->environment(),
                'user_agent' => $request->userAgent(),
            ]);
        } else {
            // 2️⃣ HMAC 验证（生产用，防止被乱 call）

            $receivedHmac = $payload['hmac'] ?? null;

            if (! $receivedHmac) {
                Log::warning('HitPay webhook missing hmac', ['payload' => $payload]);
                return response('Missing hmac', 400);
            }

            // 验签时不能包含 hmac 自己
            unset($payload['hmac']);

            // 🔑 这里用的是 config/services.php 里的 webhook_salt
            //   'hitpay' => [
            //       'webhook_salt' => env('HITPAY_WEBHOOK_SALT'),
            //   ]
            $secret = config('services.hitpay.webhook_salt')
                ?: config('services.hitpay.salt'); // 没设就 fallback

            // 为了稳定，先按 key 排序再 build query
            ksort($payload);
            $queryString = http_build_query($payload);

            $calculated = hash_hmac('sha256', $queryString, $secret);

            if (! hash_equals($calculated, $receivedHmac)) {
                Log::warning('HitPay webhook invalid signature', [
                    'payload'    => $payload,
                    'query'      => $queryString,
                    'calculated' => $calculated,
                    'received'   => $receivedHmac,
                ]);

                return response('Invalid signature', 400);
            }

            Log::info('HitPay webhook signature verified');
        }

        /**
         * 3️⃣ 用 reference_number 找订单
         *    （你 createPayment 那边已经把 order_no 放在 reference_number）
         */
        $reference = $payload['reference_number'] ?? null;

        if (! $reference) {
            Log::warning('HitPay webhook missing reference_number', ['payload' => $payload]);
            return response('Missing reference_number', 400);
        }

        /** @var \App\Models\Order|null $order */
        $order = Order::where('order_no', $reference)->first();

        if (! $order) {
            Log::warning('HitPay webhook order not found', ['reference' => $reference]);
            return response('Order not found', 404);
        }

        $oldStatus = $order->status;

        // HitPay 回来的 status（可能是 completed / succeeded / failed / pending 等）
        $statusRaw = $payload['status'] ?? '';
        $status    = strtolower($statusRaw);

        Log::info('HitPay webhook order status', [
            'order_no'      => $order->order_no,
            'hitpay_status' => $statusRaw,
            'old_status'    => $oldStatus,
        ]);

        /**
         * 4️⃣ 根据 HitPay status 更新订单
         */

        // ✅ 付款成功
        if (in_array($status, ['succeeded', 'completed', 'success', 'paid'], true)) {

            // 避免重复改 & 重复发信
            $alreadyPaid = $order->status === 'paid';

            $order->update([
                'status'         => 'paid',                              // 你的业务状态
                'payment_status' => $statusRaw ?: 'completed',          // 记录第三方原始状态（如果你有这栏位）
            ]);

            Log::info('HitPay webhook set order to paid', [
                'order_no'      => $order->order_no,
                'already_paid'  => $alreadyPaid,
            ]);

            // 只在第一次从「非 paid」变成 paid 的时候发邮件
            if (! $alreadyPaid) {
                try {
                    if ($order->customer_email) {
                        Mail::to($order->customer_email)
                            ->send(new OrderPlacedMail($order));
                    }

                    if (config('mail.admin_address')) {
                        Mail::to(config('mail.admin_address'))
                            ->send(new AdminOrderNotificationMail($order));
                    }

                    Log::info('HitPay webhook emails sent for order ' . $order->order_no);
                } catch (\Throwable $e) {
                    Log::error('HitPay webhook email failed for ' . $order->order_no . ' : ' . $e->getMessage());
                }
            }
        }
        // ❌ 付款失败 / 取消
        elseif (in_array($status, ['failed', 'cancelled', 'canceled', 'void'], true)) {
            $order->update([
                // 看你自己业务要不要把 status 也改成 failed
                // 'status' => 'failed',
                'payment_status' => $statusRaw ?: 'failed',
            ]);

            Log::info('HitPay webhook marked payment as failed', [
                'order_no' => $order->order_no,
            ]);
        }
        // 其他状态（pending、refund 等），先只记 log，不改状态
        else {
            Log::info('HitPay webhook unhandled status', [
                'order_no' => $order->order_no,
                'status'   => $statusRaw,
            ]);
        }

        // 5️⃣ 一定要回 200，HitPay 才不会一直 retry
        return response('OK', 200);
    }



    // public function handleWebhook(Request $request)
    // {
    //     \Log::info('HitPay API webhook TEST', [
    //         'payload' => $request->all(),
    //         'headers' => $request->headers->all(),
    //     ]);

    //     // 不做任何验证，固定回 200
    //     return response()->json([
    //         'ok'      => true,
    //         'message' => 'Webhook received (test)',
    //     ], 200);
    // }
}
