<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminOrderCompletedMail;
use Illuminate\Http\Request;

class AccountOrderController extends Controller
{
    public function index(Request $request)
    {
        $user   = $request->user();
        $status = $request->get('status', 'all');
        $orderNo = $request->get('order_no');

        // 全部订单（collection，用来算 badge 数量）
        $allOrders = $user->orders()->latest()->get();

        // 当前过滤订单
        $query = $user->orders()->latest();

        // 按 status 过滤（除了 all）
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Filter by order number
        if (!empty($orderNo)) {
            $query->where('order_no', 'like', "%{$orderNo}%");
        }

        // Pagination — recommended
        $orders = $query->paginate(3)->withQueryString();


        return view('account.orders.index', compact(
            'orders',
            'allOrders',
            'status',
            'orderNo'
        ));
    }

    public function show(Order $order)
    {
        // 确保是自己的订单
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // 预加载 items，避免 N+1
        $order->load('items');

        return view('account.orders.show', compact('order'));
    }

    public function markCompleted(Order $order)
    {
        // 只允许订单本人
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // 只有 shipped 才允许完成
        if ($order->status !== 'shipped') {
            return back()->with('error', 'Order is not shipped yet.');
        }

        $oldStatus = $order->status;

        // 更新为 completed
        $order->update([
            'status' => 'completed',
        ]);

        $order->refresh();

        // ✅ 通知 Admin（用户确认收货）
        if ($order->customer_email) {

            Log::info('📩 Sending AdminOrderCompletedMail', [
                'order_no' => $order->order_no,
                'user'     => $order->customer_email,
                'to'       => config('mail.admin_address', env('MAIL_ADMIN_ADDRESS')),
                'old'      => $oldStatus,
                'new'      => $order->status,
            ]);

            Mail::to(config('mail.admin_address', env('MAIL_ADMIN_ADDRESS')))
                ->send(new AdminOrderCompletedMail($order, $oldStatus, $order->status));

            Log::info('✅ AdminOrderCompletedMail sent successfully', [
                'order_no' => $order->order_no,
            ]);
        }


        return back()->with('success', 'Order marked as received. Thank you!');
    }
}
