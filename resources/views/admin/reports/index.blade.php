@extends('admin.layouts.app')

@section('content')
    {{-- Header --}}
    {{-- Header --}}
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Analytics Overview</h1>
            {{-- <p class="text-sm text-gray-500 mt-1">
                Real-time insight into your sales performance and customer growth.
            </p> --}}
        </div>

        {{-- Filters --}}
        <div class="flex flex-col md:flex-row md:items-center gap-3 w-full md:w-auto justify-end">
            {{-- Quick Range --}}
            <div
                class="inline-flex p-1 bg-gray-100/50 rounded-2xl border border-gray-200/60 backdrop-blur-sm self-stretch md:self-auto">
                @foreach (['today' => 'Today', '7d' => '7 Days', '30d' => '30 Days'] as $val => $label)
                    <a href="{{ route('admin.reports.index', ['range' => $val]) }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 {{ ($activeRange ?? '30d') === $val ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            {{-- Date Range + Apply + Export --}}
            <form method="GET" action="{{ route('admin.reports.index') }}" class="flex items-center gap-2 justify-end">
                <input type="hidden" name="range" value="custom">

                {{-- Date inputs --}}
                <div
                    class="flex items-center bg-white border border-gray-200 rounded-2xl px-3 py-2 shadow-sm focus-within:ring-2 focus-within:ring-[#D4AF37]/20">
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="border-none text-xs bg-transparent py-1 focus:ring-0 min-w-[110px]">
                    <span class="text-gray-300 px-1 text-xs">/</span>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="border-none text-xs bg-transparent py-1 focus:ring-0 min-w-[110px]">
                </div>

                {{-- Apply --}}
                <button type="submit"
                    class="bg-[#D4AF37] hover:bg-[#bfa032] text-white px-5 py-2 rounded-2xl text-xs font-bold shadow-lg shadow-[#D4AF37]/20 transition-all active:scale-95 whitespace-nowrap">
                    Apply
                </button>

                {{-- Export CSV：同一个 form，只是换 action --}}
                <button type="submit" formaction="{{ route('admin.reports.export') }}" formmethod="GET"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl
                        text-xs font-bold whitespace-nowrap
                        text-white
                        bg-[#217346]
                        border border-[#1b5c39]
                        hover:bg-[#1b5c39]
                        active:scale-95
                        shadow-lg shadow-[#217346]/25
                        transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                    </svg>
                    Export CSV
                </button>

            </form>
        </div>
    </div>


    @php
        $cardClass =
            'relative overflow-hidden bg-white p-6 rounded-3xl border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300 hover:shadow-[0_20px_40px_rgba(0,0,0,0.06)] hover:-translate-y-1';
        $statusSummary = $salesByStatus ?? [];
        $paymentSummary = $salesByPayment ?? [];
        $topProductsList = $topProducts ?? [];
    @endphp

    {{-- KPI Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="{{ $cardClass }}">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-amber-50 rounded-2xl text-[#D4AF37]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Sales</p>
            <h3 class="text-2xl font-black text-gray-900 mt-1">RM {{ number_format($totalSales ?? 0, 2) }}</h3>
            <div class="mt-4 flex items-center text-xs">
                <span
                    class="px-2 py-0.5 rounded-full bg-amber-100 text-[#8f6a10] font-bold">{{ $reportRangeLabel ?? 'Period' }}</span>
            </div>
        </div>

        <div class="{{ $cardClass }}">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-blue-50 rounded-2xl text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Orders</p>
            <h3 class="text-2xl font-black text-gray-900 mt-1">{{ number_format($totalOrders ?? 0) }}</h3>
            <p class="text-xs text-gray-400 mt-4 font-medium italic">Avg. {{ number_format($ordersPerDay ?? 0, 1) }}
                orders/day</p>
        </div>

        <div class="{{ $cardClass }}">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-emerald-50 rounded-2xl text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Average Order Value</p>
            <h3 class="text-2xl font-black text-gray-900 mt-1">RM {{ number_format($averageOrderValue ?? 0, 2) }}</h3>
            <p class="text-xs text-emerald-600 mt-4 font-bold">Sales/Orders</p>
        </div>

        <div class="{{ $cardClass }}">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-purple-50 rounded-2xl text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a3 3 0 11-6 0 3 3 0 016 0zM9 10a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">New Customers</p>
            <h3 class="text-2xl font-black text-gray-900 mt-1">{{ number_format($newCustomers ?? 0) }}</h3>
            <p class="text-xs text-gray-400 mt-4 font-medium italic">Acquisition growth</p>
        </div>
    </div>

    {{-- Charts/Tables Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        {{-- Status Breakdown --}}
        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-xl shadow-gray-200/20 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center">
                <h2 class="font-bold text-gray-900 tracking-tight">Sales by Order Status</h2>
                <span class="text-[10px] font-bold text-gray-400 bg-gray-50 px-2 py-1 rounded-lg">LIVE DATA</span>
            </div>
            <div class="p-8">
                <div class="space-y-6">
                    @forelse ($statusSummary as $status => $row)
                        @php
                            $percentage = $totalSales > 0 ? ($row['total'] / $totalSales) * 100 : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="font-bold text-gray-700">{{ strtoupper($status) }}</span>
                                <span class="font-black text-gray-900">RM {{ number_format($row['total'], 2) }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-1.5">
                                <div class="bg-[#D4AF37] h-1.5 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                            <div class="flex justify-between mt-1">
                                <span class="text-xs text-gray-400 font-bold">{{ number_format($row['orders']) }}
                                    Orders</span>
                                <span class="text-xs text-gray-500 font-black">{{ number_format($percentage, 1) }}%</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <p class="text-sm text-gray-400">No status metrics found</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Payment Breakdown --}}
        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-xl shadow-gray-200/20 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50">
                <h2 class="font-bold text-gray-900 tracking-tight">Sales by Payment Method</h2>
            </div>
            <div class="p-0">
                <table class="w-full">
                    <thead>
                        <tr class="text-xs text-gray-400 uppercase tracking-widest border-b border-gray-50">
                            <th class="px-8 py-4 text-left font-bold">Method</th>
                            <th class="px-8 py-4 text-right font-bold">Orders</th>
                            <th class="px-8 py-4 text-right font-bold">Total Sales</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($paymentSummary as $method => $row)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-4 text-sm font-bold text-gray-700">{{ $method }}</td>
                                <td class="px-8 py-4 text-sm text-right text-gray-500">{{ number_format($row['orders']) }}
                                </td>
                                <td class="px-8 py-4 text-sm text-right font-black text-gray-900">RM
                                    {{ number_format($row['total'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-20 text-gray-400 text-sm italic">Pending channel
                                    data...</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Best Sellers --}}
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-xl shadow-gray-200/20 overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-50 flex items-center gap-3">
            <div class="w-2 h-6 bg-[#D4AF37] rounded-full"></div>
            <h2 class="font-bold text-gray-900 tracking-tight text-lg">Top Products</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/50 text-xs text-gray-400 uppercase tracking-widest">
                        <th class="px-8 py-4 text-left font-bold">Product Name</th>
                        <th class="px-8 py-4 text-right font-bold">Quantity Sold</th>
                        <th class="px-8 py-4 text-right font-bold">Sales(RM)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($topProductsList as $row)
                        <tr class="group hover:bg-gray-50/50 transition-colors">
                            <td class="px-8 py-5">
                                <span class="text-sm font-bold text-gray-800 group-hover:text-[#D4AF37] transition-colors">
                                    {{ $row['name'] ?? 'General Merchandise' }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-right font-medium text-gray-600">
                                <span
                                    class="bg-gray-100 px-3 py-1 rounded-lg text-xs font-black">{{ number_format($row['qty'] ?? 0) }}</span>
                            </td>
                            <td class="px-8 py-5 text-right font-black text-gray-900 text-sm italic">
                                RM {{ number_format($row['total'] ?? 0, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-8 py-20 text-center text-gray-400 italic">No products matched the
                                current filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
