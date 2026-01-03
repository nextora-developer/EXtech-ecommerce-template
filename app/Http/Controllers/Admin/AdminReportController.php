<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

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
            ->where('is_admin', false)
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

    public function export(Request $request)
    {
        // 1) 跟 index 一样的时间过滤逻辑
        $range = $request->get('range', '30d');
        $startDateInput = $request->get('start_date');
        $endDateInput   = $request->get('end_date');

        $end   = now();
        $start = now()->subDays(29)->startOfDay();
        $reportRangeLabel = 'Last 30 Days';

        switch ($range) {
            case 'today':
                $start = now()->startOfDay();
                $end   = now()->endOfDay();
                $reportRangeLabel = 'Today';
                break;

            case '7d':
                $start = now()->subDays(6)->startOfDay();
                $end   = now()->endOfDay();
                $reportRangeLabel = 'Last 7 Days';
                break;

            case '30d':
                $start = now()->subDays(29)->startOfDay();
                $end   = now()->endOfDay();
                $reportRangeLabel = 'Last 30 Days';
                break;

            case 'custom':
                if ($startDateInput && $endDateInput) {
                    $start = Carbon::parse($startDateInput)->startOfDay();
                    $end   = Carbon::parse($endDateInput)->endOfDay();
                    $reportRangeLabel = $start->format('d M Y') . ' - ' . $end->format('d M Y');
                } else {
                    $range = '30d';
                }
                break;
        }

        // 2) 基础订单查询
        $ordersQuery = Order::query()
            ->whereNotNull('created_at')
            ->whereBetween('created_at', [$start, $end]);

        // 3) 先从 DB 拿「按天汇总」的 raw 数据
        //    注意：DATE(created_at) 会变成类似 '2026-01-01'
        $rawDaily = (clone $ordersQuery)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(total) as total')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date'); // 方便后面用日期当 key 找

        // 4) 用 CarbonPeriod 从 start ~ end 生成每一天，填补没有订单的天（变 0）
        $period = CarbonPeriod::create(
            $start->copy()->startOfDay(),
            $end->copy()->startOfDay()
        );

        $dailyStats = [];

        foreach ($period as $date) {
            $key = $date->format('Y-m-d');

            $row = $rawDaily->get($key); // 可能是 null（那天没订单）

            $ordersCount = $row ? (int) $row->orders : 0;
            $totalSales  = $row ? (float) $row->total : 0.0;
            $avgOrder    = $ordersCount > 0 ? $totalSales / $ordersCount : 0.0;

            $dailyStats[] = [
                'date'           => $key,
                'orders'         => $ordersCount,
                'total_sales'    => $totalSales,
                'avg_order'      => $avgOrder,
            ];
        }

        // 5) 生成 CSV 下载（一行 = 一天）
        $filename = 'daily_sales_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($dailyStats, $reportRangeLabel) {
            $handle = fopen('php://output', 'w');

            // 标题 + 时间范围
            fputcsv($handle, ['Daily Sales Report', $reportRangeLabel]);
            fputcsv($handle, []); // 空行

            // 表头
            fputcsv($handle, [
                'Date',
                'Orders',
                'Total Sales',
                'Average Order Value',
            ]);

            // 每天一行
            foreach ($dailyStats as $day) {
                fputcsv($handle, [
                    $day['date'],
                    $day['orders'],
                    number_format($day['total_sales'], 2, '.', ''),    // 例如 123.45
                    number_format($day['avg_order'], 2, '.', ''),
                ]);
            }

            fclose($handle);
        };

        return response()->streamDownload($callback, $filename, $headers);
    }
}
