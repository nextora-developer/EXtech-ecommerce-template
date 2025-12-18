@extends('admin.layouts.app')

@section('content')
    @php
        $fullAddress = trim(
            ($order->address_line1 ?? '') .
                "\n" .
                ($order->address_line2 ? $order->address_line2 . "\n" : '') .
                ($order->postcode ?? '') .
                ' ' .
                ($order->city ?? '') .
                "\n" .
                ($order->state ?? ''),
        );
    @endphp

    <div class="flex items-start justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-semibold text-gray-900">Order {{ $order->order_no }}</h1>
            <p class="text-sm text-gray-500 mt-1">Review customer info and update order progress.</p>
        </div>

        <a href="{{ route('admin.orders.index') }}"
            class="px-4 py-2 rounded-xl bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 transition">
            Back
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        {{-- LEFT: Customer + Address + Meta --}}
        <div
            class="lg:col-span-2 bg-white border border-[#D4AF37]/18 rounded-2xl
                    shadow-[0_18px_40px_rgba(0,0,0,0.06)] overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="font-semibold text-gray-900">Order Summary</div>

                @php
                    $status = strtoupper($order->status);

                    $styles = [
                        'PENDING' => 'border-yellow-500 bg-yellow-50 text-yellow-700',
                        'PAID' => 'border-green-500 bg-green-50 text-green-700',
                        'PROCESSING' => 'border-indigo-500 bg-indigo-50 text-indigo-700',
                        'SHIPPED' => 'border-blue-500 bg-blue-50 text-blue-700',
                        'COMPLETED' => 'border-emerald-500 bg-emerald-50 text-emerald-700',
                        'CANCELLED' => 'border-red-500 bg-red-50 text-red-700',
                    ];

                    $color = $styles[$status] ?? 'border-gray-400 bg-gray-100 text-gray-700';
                @endphp

                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold {{ $color }}">
                    {{ $status }}
                </span>

            </div>

            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <div class="text-gray-500 text-xs">Customer Name</div>
                    <div class="font-medium text-gray-900">{{ $order->customer_name ?? '-' }}</div>
                </div>

                <div>
                    <div class="text-gray-500 text-xs">Customer Phone</div>
                    <div class="font-medium text-gray-900">{{ $order->customer_phone ?? '-' }}</div>
                </div>

                <div>
                    <div class="text-gray-500 text-xs">Order ID</div>
                    <div class="font-medium text-gray-900">#{{ $order->id }}</div>
                </div>

                <div>
                    <div class="text-gray-500 text-xs">Created</div>
                    <div class="font-medium text-gray-900">{{ optional($order->created_at)->format('Y-m-d H:i') }}</div>
                </div>

                <div class="md:col-span-2">
                    <div class="text-gray-500 text-xs">Shipping Address</div>
                    <div class="mt-1 font-medium text-gray-900 whitespace-pre-line">
                        {{ $fullAddress ?: '-' }}
                    </div>
                </div>
            </div>

            {{-- Amount breakdown --}}
            <div class="px-5 py-5 border-t border-gray-100">
                <div class="font-semibold text-gray-900 mb-3">Amount</div>

                <div class="space-y-2 text-sm">
                    <div class="flex items-center justify-between">
                        <div class="text-gray-500">Subtotal</div>
                        <div class="font-medium text-gray-900">
                            RM {{ number_format(($order->subtotal_cents ?? 0) / 100, 2) }}
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="text-gray-500">Shipping</div>
                        <div class="font-medium text-gray-900">
                            RM {{ number_format(($order->shipping_cents ?? 0) / 100, 2) }}
                        </div>
                    </div>

                    <div class="h-px bg-gray-100 my-2"></div>

                    <div class="flex items-center justify-between">
                        <div class="text-gray-900 font-semibold">Total</div>
                        <div class="text-gray-900 font-semibold text-lg">
                            RM {{ number_format(($order->total_cents ?? 0) / 100, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT: Update Status --}}
        <div
            class="bg-white border border-[#D4AF37]/18 rounded-2xl p-5
                    shadow-[0_18px_40px_rgba(0,0,0,0.06)]">
            <div class="font-semibold text-gray-900 mb-1">Update Status</div>
            <div class="text-sm text-gray-500 mb-4">Keep status flow consistent for reporting.</div>

            <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="space-y-3">
                @csrf

                <label class="text-xs text-gray-500">Status</label>
                <select name="status"
                    class="w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                    @foreach (['pending', 'paid', 'processing', 'shipped', 'completed', 'cancelled'] as $s)
                        <option value="{{ $s }}" @selected($order->status === $s)>{{ strtoupper($s) }}</option>
                    @endforeach
                </select>

                <button
                    class="w-full mt-2 px-4 py-2 rounded-xl
                               bg-[#D4AF37]/15 border border-[#D4AF37]/30 text-[#8f6a10]
                               hover:bg-[#D4AF37]/20 transition font-semibold">
                    Save Changes
                </button>
            </form>

            {{-- Quick info --}}
            <div class="mt-6 rounded-xl border border-gray-200 bg-gray-50 p-4">
                <div class="text-xs text-gray-500">Order No</div>
                <div class="font-semibold text-gray-900">{{ $order->order_no }}</div>

                <div class="mt-3 text-xs text-gray-500">Customer</div>
                <div class="font-semibold text-gray-900">{{ $order->customer_name ?? '-' }}</div>
            </div>
        </div>
    </div>
@endsection
