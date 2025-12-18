@extends('admin.layouts.app')

@section('content')
    <div class="flex items-start justify-between gap-4 mb-6">
        <div>
            {{-- <div class="text-xs tracking-[0.35em] text-[#8f6a10]/60 mb-2">BRIF ADMIN</div> --}}
            <h1 class="text-3xl font-semibold text-gray-900">Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">Orders & revenue overview</p>
        </div>
        <div class="flex items-center gap-2 mt-3">
            <span
                class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-white border border-[#D4AF37]/20 text-gray-700">
                Today Orders: <span class="ml-1 font-semibold text-gray-900">{{ $todayOrders }}</span>
            </span>

            <span
                class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-white border border-[#D4AF37]/20 text-gray-700">
                Today Revenue: <span class="ml-1 font-semibold text-gray-900">RM
                    {{ number_format($todayRevenueCents / 100, 2) }}</span>
            </span>
        </div>

        <a href="{{ route('admin.orders.index') }}"
            class="px-4 py-2 rounded-xl bg-white border border-[#D4AF37]/35 text-[#8f6a10]
                  hover:bg-[#D4AF37]/10 transition">
            View Orders
        </a>
    </div>

    @php
        $card = "rounded-2xl bg-white border border-[#D4AF37]/18
                 shadow-[0_18px_40px_rgba(0,0,0,0.08)]
                 p-5";
        $label = 'text-xs text-gray-500';
        $value = 'mt-2 text-3xl font-semibold text-gray-900';
        $hint = 'mt-2 text-xs text-gray-500';
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="{{ $card }}">
            <div class="flex items-center justify-between">
                <div class="{{ $label }}">Total Orders</div>
                <div class="h-2 w-2 rounded-full bg-[#D4AF37]"></div>
            </div>
            <div class="{{ $value }}">{{ $totalOrders }}</div>
            <div class="mt-3 h-[2px] w-10 rounded bg-[#D4AF37]/80"></div>
            <div class="{{ $hint }}">All time</div>
        </div>

        <div class="{{ $card }}">
            <div class="flex items-center justify-between">
                <div class="{{ $label }}">Pending</div>
                <div class="h-2 w-2 rounded-full bg-[#D4AF37]/60"></div>
            </div>
            <div class="{{ $value }}">{{ $pendingOrders }}</div>
            <div class="{{ $hint }}">Awaiting payment / confirmation</div>
        </div>

        <div class="{{ $card }}">
            <div class="flex items-center justify-between">
                <div class="{{ $label }}">Paid</div>
                <div class="h-2 w-2 rounded-full bg-[#D4AF37]/60"></div>
            </div>
            <div class="{{ $value }}">{{ $paidOrders }}</div>
            <div class="{{ $hint }}">Ready to process</div>
        </div>

        <div class="{{ $card }}">
            <div class="flex items-center justify-between">
                <div class="{{ $label }}">Revenue</div>
                <div class="h-2 w-2 rounded-full bg-[#D4AF37]/60"></div>
            </div>
            <div class="mt-2 text-3xl font-semibold text-gray-900">
                RM {{ number_format($revenueCents / 100, 2) }}
            </div>
            <div class="{{ $hint }}">Paid + Processing + Shipped + Completed</div>
        </div>
    </div>

    <div
        class="mt-6 rounded-2xl bg-white border border-[#D4AF37]/18
                shadow-[0_18px_40px_rgba(0,0,0,0.08)] overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="h-2 w-2 rounded-full bg-[#D4AF37]"></div>
                <div class="font-semibold text-gray-900">Latest Orders</div>
            </div>
            <div class="text-xs text-gray-500">Last 10</div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-left text-gray-500">
                    <tr>
                        <th class="px-5 py-3 font-medium">Order No</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium">Total</th>
                        <th class="px-5 py-3 font-medium">Created</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="text-gray-800">
                    @forelse ($latestOrders as $o)
                        <tr class="border-t border-gray-100 hover:bg-[#D4AF37]/10 transition">
                            <td class="px-5 py-3 font-medium">{{ $o->order_no }}</td>
                            <td class="px-5 py-3">
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-md text-xs
                                    border border-[#D4AF37]/30 bg-[#D4AF37]/10 text-[#8f6a10]">
                                    {{ strtoupper($o->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-3">RM {{ number_format($o->total_cents / 100, 2) }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $o->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-5 py-3 text-right">
                                <a class="text-[#8f6a10] hover:underline" href="{{ route('admin.orders.show', $o) }}">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-5 py-8 text-gray-500" colspan="5">No orders yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
