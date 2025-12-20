<?php

namespace App\Http\Controllers;

use App\Models\Order;
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
}
