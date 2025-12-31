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
    public function index(Request $request)
    {
        // 1) 处理时间区间（range = today / 7d / 30d / custom）
        $range = $request->get('range', '30d'); // 默认 30d
        $startDateInput = $request->get('start_date');
        $endDateInput   = $request->get('end_date');

        $end   = now();
        $start = now()->subDays(29)->startOfDay(); // 默认：过去 30 天
        $reportRangeLabel = 'Last 30 Days';

        switch ($range) {
            case 'today':
                $start = now()->startOfDay();
                $end   = now()->endOfDay();
                $reportRangeLabel = 'Today';
                break;

            case '7d':
                $start = now()->subDays(6)->startOfDay(); // 包含今天共 7 天
                $end   = now()->endOfDay();
                $reportRangeLabel = 'Last 7 Days';
                break;

            case '30d':
                $start = now()->subDays(29)->startOfDay();
                $end   = now()->endOfDay();
                $reportRangeLabel = 'Last 30 Days';
                break;

            case 'custom':
                // 如果你之后在 UI 做 custom date picker，就可以传 start_date / end_date
                if ($startDateInput && $endDateInput) {
                    $start = Carbon::parse($startDateInput)->startOfDay();
                    $end   = Carbon::parse($endDateInput)->endOfDay();
                    $reportRangeLabel = $start->format('d M Y') . ' - ' . $end->format('d M Y');
                } else {
                    // 没给日期就 fall back to 30d
                    $range = '30d';
                }
                break;
        }

        $activeRange = $range;

        /**
         * 2) 基础订单查询
         * ✳️ 这里假设有 paid_at 字段（已付款时间），如果没有就改成 created_at
         */
        $ordersQuery = Order::query()
            ->whereNotNull('created_at') // 如果你没有 paid_at，删掉这行
            ->whereBetween('created_at', [$start, $end]);

        // 3) Total Sales
        $totalSales = (clone $ordersQuery)->sum('total'); // 改成你的金额字段，如 total / total_amount 等

        // 4) Total Orders
        $totalOrders = (clone $ordersQuery)->count();

        // 5) Orders per Day
        $days = max($start->diffInDays($end) + 1, 1);
        $ordersPerDay = $days > 0 ? $totalOrders / $days : 0;

        // 6) Average Order Value
        $averageOrderValue = $totalOrders > 0
            ? $totalSales / $totalOrders
            : 0;

        // 7) New Customers (假设 customers = users 表)
        $newCustomers = User::query()
            ->whereBetween('created_at', [$start, $end])
            ->count();

        // 8) Sales by Status
        // $row = ['status' => 'PAID', 'orders' => xxx, 'total' => xxx]
        $salesByStatusCollection = (clone $ordersQuery)
            ->selectRaw('status, COUNT(*) as orders, SUM(total) as total')
            ->groupBy('status')
            ->get();

        $salesByStatus = $salesByStatusCollection
            ->mapWithKeys(function ($row) {
                return [
                    $row->status => [
                        'orders' => (int) $row->orders,
                        'total'  => (float) $row->total,
                    ],
                ];
            })
            ->toArray();

        // 9) Sales by Payment Method
        // 假设 orders 表有 payment_method 字段 (e.g. 'FPX', 'TNG', 'COD')
        $salesByPaymentCollection = (clone $ordersQuery)
            ->selectRaw('payment_method_name as payment_method, COUNT(*) as orders, SUM(total) as total')
            ->groupBy('payment_method_name')
            ->get();

        $salesByPayment = $salesByPaymentCollection
            ->mapWithKeys(function ($row) {
                return [
                    $row->payment_method ?? 'Unknown' => [
                        'orders' => (int) $row->orders,
                        'total'  => (float) $row->total,
                    ],
                ];
            })
            ->toArray();


        // 10) Top Products
        // ✳️ 这里用 DB::table 假设：
        // - orders.id
        // - order_items.order_id, order_items.product_id, order_items.quantity, order_items.line_total
        // - products.id, products.name
        // 你可以改成自己的字段 / 模型关系
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereNotNull('orders.created_at')
            ->whereBetween('orders.created_at', [$start, $end])
            ->selectRaw('products.name as name, SUM(order_items.qty) as qty, SUM(order_items.unit_price) as total')
            ->groupBy('products.name')
            ->orderByDesc('qty')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                return [
                    'name'  => $row->name,
                    'qty'   => (int) $row->qty,
                    'total' => (float) $row->total,
                ];
            })
            ->toArray();

        // 11) 把所有数据丢去 Blade
        return view('admin.reports.index', [
            'activeRange'        => $activeRange,
            'reportRangeLabel'   => $reportRangeLabel,
            'totalSales'         => $totalSales,
            'totalOrders'        => $totalOrders,
            'ordersPerDay'       => $ordersPerDay,
            'averageOrderValue'  => $averageOrderValue,
            'newCustomers'       => $newCustomers,
            'salesByStatus'      => $salesByStatus,
            'salesByPayment'     => $salesByPayment,
            'topProducts'        => $topProducts,
        ]);
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
