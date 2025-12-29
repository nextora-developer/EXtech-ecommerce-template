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

            <div class="p-5 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <div class="text-gray-500 text-xs">Customer Name</div>
                    <div class="font-medium text-gray-900">{{ $order->customer_name ?? '-' }}</div>
                </div>

                <div>
                    <div class="text-gray-500 text-xs">Customer Phone</div>
                    <div class="font-medium text-gray-900">{{ $order->customer_phone ?? '-' }}</div>
                </div>

                <div>
                    <div class="text-gray-500 text-xs">Customer Email</div>
                    <div class="font-medium text-gray-900">{{ $order->customer_email ?? '-' }}</div>
                </div>

                <div class="md:col-span-2">
                    <div class="text-gray-500 text-xs">Shipping Address</div>
                    <div class="font-medium text-gray-900">
                        {{ $fullAddress ?: '-' }}
                    </div>
                </div>

                <div>
                    <div class="text-gray-500 text-xs">Created</div>
                    <div class="font-medium text-gray-900">{{ optional($order->created_at)->format('Y-m-d H:i') }}</div>
                </div>


            </div>

            {{-- Order items --}}
            <div class="px-5 py-5 border-t border-gray-100">
                <div class="font-semibold text-gray-900 mb-3">Items</div>

                @if ($order->items && $order->items->count())
                    <div class="overflow-x-auto rounded-xl border border-gray-100">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                                <tr>
                                    <th class="px-4 py-2 text-left">Product</th>
                                    <th class="px-4 py-2 text-left">Variant</th>
                                    <th class="px-4 py-2 text-center">Qty</th>
                                    <th class="px-4 py-2 text-center">Price</th>
                                    <th class="px-4 py-2 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($order->items as $item)
                                    <tr>
                                        {{-- 产品名称：看你是存 snapshot name 还是关系 --}}
                                        <td class="px-4 py-2 align-top text-gray-900">
                                            {{ $item->product_name ?? ($item->product->name ?? '—') }}
                                        </td>

                                        {{-- Variant (badge style) --}}
                                        <td class="px-4 py-2 align-top">
                                            @php
                                                $label = $item->variant_label ?? null;
                                                $value = $item->variant_value ?? null;
                                            @endphp

                                            @if ($label || $value)
                                                <div class="mt-0.5 flex flex-wrap gap-1">
                                                    @foreach (explode('&', $label && $value ? $label . ' & ' . $value : $label ?? $value) as $part)
                                                        <span
                                                            class="inline-flex items-center px-2 py-0.5 rounded-lg
                             bg-gray-100 text-gray-700 border border-gray-200
                             text-xs font-medium">
                                                            {{ trim($part) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>


                                        {{-- 数量 --}}
                                        <td class="px-4 py-2 text-center text-gray-900">
                                            {{ $item->qty ?? 1 }}
                                        </td>

                                        {{-- 单价 --}}
                                        <td class="px-4 py-2 text-center text-gray-900">
                                            RM {{ number_format($item->unit_price, 2) }}
                                        </td>

                                        {{-- 小计 --}}
                                        <td class="px-4 py-2 text-right font-semibold text-gray-900">
                                            RM {{ number_format($item->subtotal, 2) }}
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-sm text-gray-500">No items found for this order.</p>
                @endif
            </div>


            {{-- Amount breakdown --}}
            <div class="px-5 py-5 border-t border-gray-100">
                <div class="font-semibold text-gray-900 mb-3">Amount</div>

                <div class="space-y-2 text-sm">
                    <div class="flex items-center justify-between">
                        <div class="text-gray-500">Subtotal</div>
                        <div class="font-medium text-gray-900">
                            RM {{ number_format($order->subtotal ?? 0, 2) }}
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="text-gray-500">Shipping Fee</div>
                        <div class="font-medium text-gray-900">
                            RM {{ number_format($order->shipping_fee ?? 0, 2) }}
                        </div>
                    </div>

                    <div class="h-px bg-gray-100 my-2"></div>

                    <div class="flex items-center justify-between">
                        <div class="text-gray-900 font-semibold">Total</div>
                        <div class="text-gray-900 font-semibold text-lg">
                            RM {{ number_format($order->total ?? 0, 2) }}
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
                <div class="text-xs text-gray-500">Order ID</div>
                <div class="font-semibold text-gray-900">{{ $order->id }}</div>

                <div class="mt-3 text-xs text-gray-500">Order No</div>
                <div class="font-semibold text-gray-900">{{ $order->order_no }}</div>

                <div class="mt-3 text-xs text-gray-500">Payment Method</div>
                <div class="font-semibold text-gray-900">{{ $order->payment_method_name }}</div>

                @if ($order->payment_receipt_path)
                    <div class="mt-3 text-xs text-gray-500">Payment Receipt</div>

                    <button onclick="document.getElementById('receiptModal').showModal()"
                        class="inline-flex items-center mt-1 px-3 py-1.5 rounded-lg border border-gray-300
               bg-white hover:bg-gray-100 text-sm font-medium text-gray-700">
                        View Receipt
                    </button>

                    <a href="{{ asset('storage/' . $order->payment_receipt_path) }}" download
                        class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium
          bg-[#D4AF37] text-white hover:bg-[#C49A2F]">
                        Download
                    </a>
                @endif

            </div>
        </div>
    </div>


    @if ($order->payment_receipt_path)
        <dialog id="receiptModal" class="rounded-xl p-0">
            <div class="p-4 border-b flex justify-between items-center">
                <div class="font-semibold">Payment Receipt</div>
                <button onclick="document.getElementById('receiptModal').close()">✕</button>
            </div>

            <img src="{{ asset('storage/' . $order->payment_receipt_path) }}" class="max-h-[80vh] w-auto mx-auto p-4">
        </dialog>
    @endif


@endsection
