<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // 这些先用假数据占位，之后你可以换成真实统计
        $stats = [
            'orders' => 1,
            'favorites' => 0,
            'addresses' => 0,
        ];

        $latestOrder = [
            'number' => '#ORD-5UT9LWHJ',
            'date'   => '05 Dec 2025, 14:02',
            'total'  => 'RM 22.00',
            'status' => 'Pending',
            'url'    => '#', // 将来可以改成 route('orders.show', $id)
        ];

        return view('account.index', compact('user', 'stats', 'latestOrder'));
    }

    public function orders(Request $request)
    {
        $user   = $request->user();
        $status = $request->get('status', 'all');
        $orderNo = $request->get('order_no');

        // 全部订单
        $allOrders = $user->orders()->latest()->get();

        // 当前过滤订单
        $query = $user->orders()->latest();

        // 按 status 过滤（除了 all）
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // 按订单号搜索
        if ($request->orderNo) {
            $query->where('order_no', 'like', '%' . $request->orderNo . '%');
        }

        $orders = $query->get();

        return view('account.orders', compact(
            'orders',
            'allOrders',
            'status'
        ));
    }
}
