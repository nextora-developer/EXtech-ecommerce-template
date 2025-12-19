<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalOrders   = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $paidOrders    = Order::where('status', 'paid')->count();

        // Revenue: count only orders that are "earned" (adjust if you want)
        $revenue = (int) Order::whereIn('status', ['paid', 'processing', 'shipped', 'completed'])
            ->sum('total');

        // Today's metrics (nice to have)
        $todayOrders = Order::whereDate('created_at', now()->toDateString())->count();
        $todayRevenue = (int) Order::whereDate('created_at', now()->toDateString())
            ->whereIn('status', ['paid', 'processing', 'shipped', 'completed'])
            ->sum('total');

        $latestOrders = Order::latest()->limit(10)->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'paidOrders',
            'revenue',
            'todayOrders',
            'todayRevenue',
            'latestOrders'
        ));
    }
}
