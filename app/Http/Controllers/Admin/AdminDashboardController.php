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
        $revenueCents  = (int) Order::whereIn('status', ['paid', 'processing', 'shipped', 'completed'])
            ->sum('total_cents');

        $latestOrders = Order::latest()->limit(10)->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'paidOrders',
            'revenueCents',
            'latestOrders'
        ));
    }
}
