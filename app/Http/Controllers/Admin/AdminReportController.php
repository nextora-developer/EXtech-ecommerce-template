<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function sales()
    {
        $today = Carbon::today();

        $totalSales = Order::where('status', 'COMPLETED')->sum('total_amount');
        $totalOrders = Order::count();
        $todaySales = Order::whereDate('created_at', $today)->sum('total_amount');

        return view('admin.reports.sales', compact(
            'totalSales',
            'totalOrders',
            'todaySales',
        ));
    }

    public function products()
    {
        $topProducts = Product::withCount(['orderItems as sold_qty' => function ($q) {
            $q->select(DB::raw('SUM(quantity)'));
        }])
            ->orderByDesc('sold_qty')
            ->take(10)
            ->get();

        return view('admin.reports.products', compact('topProducts'));
    }

    public function orders()
    {
        $statusCounts = Order::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.reports.orders', compact('statusCounts'));
    }

    public function customers()
    {
        $topCustomers = User::withSum('orders as total_spent', 'total_amount')
            ->orderByDesc('total_spent')
            ->take(10)
            ->get();

        return view('admin.reports.customers', compact('topCustomers'));
    }
}
