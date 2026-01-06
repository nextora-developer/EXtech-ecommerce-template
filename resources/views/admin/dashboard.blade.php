@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Dashboard</h1>
            <p class="text-sm text-gray-500">Real-time overview of your orders and revenue performance</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl border border-gray-100 shadow-sm">
                <span class="flex h-2 w-2 rounded-full bg-amber-400"></span>
                <span class="text-xs font-medium text-gray-600">
                    Today Orders: <span class="text-gray-900">{{ $todayOrders }}</span>
                </span>
            </div>
            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl border border-gray-100 shadow-sm">
                <span class="flex h-2 w-2 rounded-full bg-emerald-400"></span>
                <span class="text-xs font-medium text-gray-600">
                    Today Sales: <span class="text-gray-900">RM {{ number_format($todaySales, 2) }}</span>
                </span>
            </div>
            <a href="{{ route('admin.orders.index') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gray-900 text-white text-sm font-semibold hover:bg-gray-800 transition-all shadow-md hover:shadow-lg active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
                View All Orders
            </a>
        </div>
    </div>

    @php
        $cardStyle =
            'group bg-white p-6 rounded-2xl border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-[0_8px_30px_-4px_rgba(0,0,0,0.1)] transition-all duration-300 hover:-translate-y-1';
        $iconBox = 'w-10 h-10 rounded-lg flex items-center justify-center mb-4 transition-colors';
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="{{ $cardStyle }}">
            <div class="{{ $iconBox }} bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-500">Total Orders</p>
            <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalOrders) }}</h3>
            <p class="text-[11px] text-gray-400 mt-2 uppercase tracking-wider font-semibold">All-time Records</p>
        </div>

        <div class="{{ $cardStyle }}">
            <div class="{{ $iconBox }} bg-amber-50 text-amber-600 group-hover:bg-amber-600 group-hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-500">Pending</p>
            <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $pendingOrders }}</h3>
            <p class="text-[11px] text-amber-600/80 mt-2 font-medium">Requires payment attention</p>
        </div>

        <div class="{{ $cardStyle }}">
            <div
                class="{{ $iconBox }} bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-500">Paid</p>
            <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $paidOrders }}</h3>
            <p class="text-[11px] text-emerald-600/80 mt-2 font-medium">Awaiting fulfillment</p>
        </div>

        <div class="{{ $cardStyle }} border-r-4 border-r-[#D4AF37]">
            <div
                class="{{ $iconBox }} bg-[#D4AF37]/10 text-[#8f6a10] group-hover:bg-[#D4AF37] group-hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-500">Total Revenue</p>
            <h3 class="text-2xl font-bold text-gray-900 mt-1">RM {{ number_format($sales, 2) }}</h3>
            <p class="text-[11px] text-gray-400 mt-2 font-medium">Completed orders total</p>
        </div>
    </div>

    <div class="mt-8 bg-white rounded-2xl border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-50">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-gray-50 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-gray-900">Recent Orders</h2>
                    <p class="text-xs text-gray-400">Showing the latest 10 transactions</p>
                </div>
            </div>
            <a href="{{ route('admin.orders.index') }}"
                class="text-sm font-medium text-[#8f6a10] hover:text-[#D4AF37] transition-colors">View all →</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left bg-gray-50/50">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Order No.</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Created At</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($latestOrders as $o)
                        <tr class="group hover:bg-gray-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm font-bold text-gray-700 group-hover:text-[#8f6a10]">
                                    #{{ $o->order_no }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'PENDING' => 'bg-amber-50 text-amber-700 border-amber-100',
                                        'PAID' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                        'PROCESSING' => 'bg-blue-50 text-blue-700 border-blue-100',
                                        'SHIPPED' => 'bg-purple-50 text-purple-700 border-purple-100',
                                        'COMPLETED' => 'bg-gray-100 text-gray-700 border-gray-200',
                                        'CANCELLED' => 'bg-red-50 text-red-700 border-red-100',
                                        'FAILED' => 'bg-rose-50 text-rose-700 border-rose-100',
                                    ];
                                    $statusStyle =
                                        $statusColors[strtoupper($o->status)] ??
                                        'bg-gray-50 text-gray-600 border-gray-100';
                                @endphp
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $statusStyle }}">
                                    {{ strtoupper($o->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-semibold text-gray-900">
                                    RM {{ number_format($o->total, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-500">{{ $o->created_at->format('d M Y') }}</span>
                                <span class="text-xs text-gray-400 block">{{ $o->created_at->format('H:i A') }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.orders.show', $o) }}"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white border border-gray-200 text-gray-400 hover:text-[#D4AF37] hover:border-[#D4AF37] hover:shadow-sm transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="text-gray-200 mb-2 text-5xl">📦</div>
                                    <p class="text-gray-400 text-sm">No order data yet</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
