@extends('admin.layouts.app')

@section('content')
    <div class="flex items-start justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-semibold text-gray-900">Orders</h1>
            <p class="text-sm text-gray-500 mt-1">Search, filter, and manage order status.</p>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET"
        class="bg-white border border-[#D4AF37]/18 rounded-2xl p-4
                              shadow-[0_18px_40px_rgba(0,0,0,0.06)] mb-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <div class="md:col-span-2">
                <label class="text-xs text-gray-500">Keyword</label>
                <input name="keyword" value="{{ request('keyword') }}" placeholder="Order No / Name / Phone"
                    class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
            </div>

            <div>
                <label class="text-xs text-gray-500">Status</label>
                <select name="status"
                    class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                    <option value="">All</option>
                    @foreach ($statuses as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>{{ strtoupper($s) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-xs text-gray-500">From</label>
                <input type="date" name="from" value="{{ request('from') }}"
                    class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
            </div>

            <div>
                <label class="text-xs text-gray-500">To</label>
                <input type="date" name="to" value="{{ request('to') }}"
                    class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
            </div>
        </div>

        <div class="flex items-center gap-2 mt-4">
            <button
                class="px-4 py-2 rounded-xl bg-[#D4AF37]/15 text-[#8f6a10] border border-[#D4AF37]/30
                           hover:bg-[#D4AF37]/20 transition font-semibold">
                Apply
            </button>

            <a href="{{ route('admin.orders.index') }}"
                class="px-4 py-2 rounded-xl bg-white text-gray-700 border border-gray-200 hover:bg-gray-50 transition">
                Reset
            </a>

            <div class="ml-auto text-sm text-gray-500">
                {{ $orders->total() }} orders
            </div>
        </div>
    </form>

    {{-- Table --}}
    <div
        class="bg-white border border-[#D4AF37]/18 rounded-2xl overflow-hidden
                shadow-[0_18px_40px_rgba(0,0,0,0.06)]">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="font-semibold text-gray-900">Order List</div>
            <div class="text-xs text-gray-500">Latest first</div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-left text-gray-600 bg-gray-50">
                    <tr>
                        <th class="px-5 py-3">Order No</th>
                        <th class="px-5 py-3">Customer</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Total</th>
                        <th class="px-5 py-3">Created</th>
                        <th class="px-5 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800">
                    @forelse ($orders as $o)
                        <tr class="border-t border-gray-100 hover:bg-[#D4AF37]/10 transition">
                            <td class="px-5 py-3 font-semibold">{{ $o->order_no }}</td>

                            <td class="px-5 py-3">
                                <div class="font-medium">{{ $o->customer_name ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $o->customer_phone ?? '' }}</div>
                            </td>

                            @php
                                $status = strtoupper($o->status);

                                $colors = [
                                    'PENDING' => 'border-yellow-500 bg-yellow-50 text-yellow-700',
                                    'PAID' => 'border-green-500 bg-green-50 text-green-700',
                                    'PROCESSING' => 'border-indigo-500 bg-indigo-50 text-indigo-700',
                                    'SHIPPED' => 'border-blue-500 bg-blue-50 text-blue-700',
                                    'COMPLETED' => 'border-emerald-500 bg-emerald-50 text-emerald-700',
                                    'CANCELLED' => 'border-red-500 bg-red-50 text-red-700',
                                ];

                                $style = $colors[$status] ?? 'border-gray-400 bg-gray-100 text-gray-700'; // fallback
                            @endphp

                            <td class="px-5 py-3">
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold {{ $style }}">
                                    {{ $status }}
                                </span>
                            </td>

                            <td class="px-5 py-3">
                                RM {{ number_format(($o->total ?? 0) / 100, 2) }}
                            </td>

                            <td class="px-5 py-3 text-gray-500">
                                {{ optional($o->created_at)->format('Y-m-d H:i') }}
                            </td>

                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('admin.orders.show', $o) }}"
                                    class="text-[#8f6a10] font-semibold hover:underline">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-5 py-10 text-gray-500" colspan="6">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 border-t border-gray-100">
            {{ $orders->links() }}
        </div>
    </div>
@endsection
