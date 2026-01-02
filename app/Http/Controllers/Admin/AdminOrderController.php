<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $q = Order::query();

        // Filters
        if ($request->filled('status')) {
            $q->where('status', $request->string('status'));
        }

        if ($request->filled('keyword')) {
            $keyword = $request->string('keyword');
            $q->where(function ($qq) use ($keyword) {
                $qq->where('order_no', 'like', "%{$keyword}%")
                    ->orWhere('customer_name', 'like', "%{$keyword}%")
                    ->orWhere('customer_phone', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('from')) {
            $q->whereDate('created_at', '>=', $request->date('from'));
        }

        if ($request->filled('to')) {
            $q->whereDate('created_at', '<=', $request->date('to'));
        }

        $orders = $q->latest()->paginate(10)->withQueryString();

        $statuses = ['pending', 'paid', 'processing', 'shipped', 'completed', 'cancelled'];

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $statuses = ['pending', 'paid', 'processing', 'shipped', 'completed', 'cancelled'];

        $validated = $request->validate([
            'status'           => ['required', Rule::in($statuses)],
            'shipping_courier' => ['required_if:status,shipped,completed', 'nullable', 'string', 'max:100'],
            'tracking_number'  => ['required_if:status,shipped,completed', 'nullable', 'string', 'max:120'],
            'shipped_at'       => ['nullable', 'date'],
        ]);

        $data = [
            'status' => $validated['status'],
        ];

        // 如果订单进入 shipped / completed 阶段 → 一并保存物流资料
        if (in_array($validated['status'], ['shipped', 'completed'])) {

            $data['shipping_courier'] = $validated['shipping_courier'] ?? $order->shipping_courier;
            $data['tracking_number']  = $validated['tracking_number'] ?? $order->tracking_number;

            // 如果没有传入时间就自动写入当前时间
            $data['shipped_at'] = $validated['shipped_at'] ?? ($order->shipped_at ?? now());
        }

        $order->update($data);

        return back()->with('success', 'Order status updated.');
    }
}
