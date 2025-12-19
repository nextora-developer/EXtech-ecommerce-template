<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        
        $user = auth()->user();

        // 这些先用假数据占位，之后你可以换成真实统计
        // $stats = [
        //     'orders' => 1,
        //     'favorites' => 0,
        //     'addresses' => 0,
        // ];

        // 真实统计
        $stats = [
            'orders'    => $user->orders()->count() ?? 0,
            'favorites' => 0,
            'addresses' => $user->addresses()->count() ?? 0,
        ];

        $latestOrders = $user->orders()
            ->latest()
            ->take(5)
            ->get();

        return view('account.index', compact('user', 'stats', 'latestOrders'));
    }

    public function orders(Request $request)
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
        $orders = $query->paginate(5)->withQueryString();


        return view('account.orders', compact(
            'orders',
            'allOrders',
            'status',
            'orderNo'
        ));
    }
}
