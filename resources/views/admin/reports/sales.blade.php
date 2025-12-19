@extends('admin.layouts.app')

@section('content')
    <div class="p-6">

        <h2 class="text-xl font-semibold mb-6">Sales Report</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="p-4 border rounded-xl bg-white">
                <p class="text-gray-500 text-xs">Total Sales</p>
                <h3 class="text-lg font-bold">RM {{ number_format($totalSales, 2) }}</h3>
            </div>

            <div class="p-4 border rounded-xl bg-white">
                <p class="text-gray-500 text-xs">Total Orders</p>
                <h3 class="text-lg font-bold">{{ $totalOrders }}</h3>
            </div>

            <div class="p-4 border rounded-xl bg-white">
                <p class="text-gray-500 text-xs">Today Sales</p>
                <h3 class="text-lg font-bold">RM {{ number_format($todaySales, 2) }}</h3>
            </div>
        </div>

        {{-- placeholder for chart --}}
        <canvas id="salesChart" height="120"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Sales',
                    data: [120, 320, 450, 250, 900, 1100, 800],
                }]
            },
        });
    </script>
@endsection
