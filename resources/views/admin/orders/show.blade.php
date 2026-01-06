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

        $status = strtoupper($order->status);
        $styles = [
            'PENDING' => 'border-yellow-500 bg-yellow-50 text-yellow-700',
            'PAID' => 'border-green-500 bg-green-50 text-green-700',
            'PROCESSING' => 'border-indigo-500 bg-indigo-50 text-indigo-700',
            'SHIPPED' => 'border-blue-500 bg-blue-50 text-blue-700',
            'COMPLETED' => 'border-emerald-500 bg-emerald-50 text-emerald-700',
            'CANCELLED' => 'border-red-500 bg-red-50 text-red-700',
            'FAILED' => 'border-rose-500 bg-rose-50 text-rose-700',
        ];
        $badgeColor = $styles[$status] ?? 'border-gray-400 bg-gray-100 text-gray-700';
    @endphp

    {{-- Header Section --}}
    <div class="flex items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Order #{{ $order->order_no }}</h1>
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $badgeColor }}">
                    {{ $status }}
                </span>
            </div>
            <p class="text-sm text-gray-500 mt-1">Manage fulfillment and customer communications.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.orders.index') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white border border-gray-200 
               text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span>Back to List</span>
            </a>

            {{-- Show HitPay Dashboard Button only if payment is HitPay --}}
            @if ($order->gateway === 'hitpay' || str_contains(strtolower($order->payment_method_name ?? ''), 'hitpay'))
                <a href="https://dashboard.hit-pay.com/payments/{{ $order->payment_reference }}" target="_blank"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0F172A] text-white
                   text-sm font-bold hover:bg-black transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5
                                 c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639
                                 C20.577 16.49 16.64 19.5 12 19.5
                                 c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>

                    View in HitPay
                </a>
            @endif
        </div>



    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT COLUMN: Details & Items --}}
        <div class="lg:col-span-2 space-y-6 ">


            {{-- Customer & Shipping Card --}}
            <div class="bg-white border border-[#D4AF37]/20 rounded-2xl shadow-[0_18px_40px_rgba(0,0,0,0.04)]">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900">Delivery Information</h3>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-xs uppercase tracking-widest text-gray-400 font-bold mb-1">Customer</label>
                            <div class="font-semibold text-gray-900 text-base">{{ $order->customer_name ?? '-' }}</div>
                            <div class="text-gray-500 mt-0.5">{{ $order->customer_email ?? 'No Email' }}</div>
                            <div class="text-gray-500">{{ $order->customer_phone ?? '-' }}</div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs uppercase tracking-widest text-gray-400 font-bold mb-1">Shipping
                            To</label>
                        <div class="text-gray-700 leading-relaxed font-medium">
                            {!! nl2br(e($fullAddress)) ?: '<span class="text-gray-400 italic">No address provided</span>' !!}
                        </div>
                    </div>

                </div>

                {{-- Order Items Table --}}
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30">
                    <h3 class="font-bold text-gray-900 mb-4">Line Items</h3>
                    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
                        <table class="w-full text-sm">
                            <thead
                                class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500">
                                <tr>
                                    <th class="px-4 py-3 font-bold text-left">Photo</th>
                                    <th class="px-4 py-3 font-bold text-left">Product Details</th>
                                    <th class="px-4 py-3 font-bold text-center">Qty</th>
                                    <th class="px-4 py-3 font-bold text-right">Price</th>
                                    <th class="px-4 py-3 font-bold text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($order->items as $item)
                                    <tr>
                                        {{-- Photo --}}
                                        <td class="px-4 py-4">
                                            @if ($item->product?->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                                    class="w-12 h-12 rounded object-cover border border-gray-200">
                                            @else
                                                <div
                                                    class="w-12 h-12 rounded bg-gray-100 border border-gray-200 flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-300"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </td>

                                        {{-- Product Details (文字单独一格) --}}
                                        <td class="px-4 py-4">
                                            <div class="font-bold text-gray-900 text-sm">
                                                {{ $item->product_name ?? ($item->product->name ?? 'Unknown Product') }}
                                            </div>

                                            @if ($item->variant_label || $item->variant_value)
                                                <div class="flex gap-1 mt-1.5">
                                                    @php
                                                        $parts = explode(
                                                            '&',
                                                            ($item->variant_label ?? '') .
                                                                ' & ' .
                                                                ($item->variant_value ?? ''),
                                                        );
                                                    @endphp
                                                    @foreach ($parts as $part)
                                                        @if (trim($part))
                                                            <span
                                                                class="inline-block px-2 py-0.5 bg-[#D4AF37]/5 border border-[#D4AF37]/20
                                   text-[#8f6a10] text-[10px] font-bold rounded-md uppercase">
                                                                {{ trim($part) }}
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>

                                        {{-- Qty --}}
                                        <td class="px-4 py-4 text-center font-medium text-gray-600">
                                            x{{ $item->qty ?? 1 }}
                                        </td>

                                        {{-- Price --}}
                                        <td class="px-4 py-4 text-right text-gray-600 italic">
                                            RM {{ number_format($item->unit_price, 2) }}
                                        </td>

                                        {{-- Total --}}
                                        <td class="px-4 py-4 text-right font-bold text-gray-900">
                                            RM {{ number_format($item->subtotal, 2) }}
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-gray-400 italic">No items
                                            attached to this order.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Totals + Remark Area --}}
                <div class="p-8 bg-white border-t border-gray-100 grid grid-cols-1 md:grid-cols-3 gap-8 items-start">

                    {{-- 🟡 Order Remark (Left, Span 2) --}}
                    <div class="md:col-span-2 space-y-2">
                        <label class="block text-xs uppercase tracking-[0.2em] text-red-600 font-extrabold">
                            Order Remark
                        </label>

                        <div class="relative group">
                            <div
                                class="min-h-[80px] w-full text-sm leading-relaxed px-5 py-4 rounded-2xl border border-gray-100 bg-gray-50/50 transition-colors group-hover:bg-gray-50">
                                @if ($order->remark)
                                    <p class="text-gray-700 whitespace-pre-line break-words">{{ trim($order->remark) }}</p>
                                @else
                                    <p class="text-gray-400 italic font-light">No special instructions provided for this
                                        order.</p>
                                @endif
                            </div>
                            {{-- Optional: Subtle Decorative Quote Icon --}}
                            <div class="absolute top-3 right-4 opacity-[0.03] pointer-events-none">
                                <svg class="w-8 h-8 text-black" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C19.5693 16 20.017 15.5523 20.017 15V9C20.017 8.44772 19.5693 8 19.017 8H15.017C14.4647 8 14.017 7.55228 14.017 7V3L21.017 3C22.1216 3 23.017 3.89543 23.017 5V15C23.017 18.3137 20.3307 21 17.017 21H14.017ZM3.0166 21L3.0166 18C3.0166 16.8954 3.91203 16 5.0166 16H8.0166C8.56888 16 9.0166 15.5523 9.0166 15V9C9.0166 8.44772 8.56888 8 8.0166 8H4.0166C3.46432 8 3.0166 7.55228 3.0166 7V3L10.0166 3C11.1212 3 12.0166 3.89543 12.0166 5V15C12.0166 18.3137 9.33031 21 6.0166 21H3.0166Z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- 🟢 Totals (Right, Span 1) --}}
                    <div class="md:col-span-1 flex md:justify-end">
                        <div
                            class="w-full max-w-xs bg-white rounded-2xl border border-gray-50 p-4 shadow-sm md:shadow-none md:border-none md:p-0">
                            <div class="space-y-3">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500 font-medium">Subtotal</span>
                                    <span class="text-gray-900 font-bold tracking-tight">
                                        <span
                                            class="text-sm text-gray-400 mr-0.5 font-normal">RM </span>{{ number_format($order->subtotal ?? 0, 2) }}
                                    </span>
                                </div>

                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500 font-medium">Shipping</span>
                                    <span
                                        class="font-bold tracking-tight {{ ($order->shipping_fee ?? 0) > 0 ? 'text-gray-900' : 'text-green-600' }}">
                                        @if (($order->shipping_fee ?? 0) > 0)
                                            <span
                                                class="text-sm text-gray-400 mr-0.5 font-normal">RM </span>{{ number_format($order->shipping_fee, 2) }}
                                        @else
                                            FREE
                                        @endif
                                    </span>
                                </div>

                                <div class="pt-4 mt-2 border-t border-gray-100 flex justify-between items-end">
                                    <div class="flex flex-col">
                                        {{-- <span
                                            class="text-[10px] uppercase tracking-widest text-gray-400 font-bold leading-none mb-1">Total
                                            Amount</span> --}}
                                        <span class="text-base font-black text-gray-900 leading-none">Grand Total</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-3xl font-black text-[#8f6a10] tracking-tighter">
                                            <span
                                                class="text-sm font-bold mr-1">RM</span>{{ number_format($order->total ?? 0, 2) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>




                {{-- Totals Area --}}
                {{-- <div class="p-6 bg-white border-t border-gray-100 flex justify-end">
                    <div class="w-full max-w-xs space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Subtotal</span>
                            <span class="font-semibold text-gray-900">RM
                                {{ number_format($order->subtotal ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Shipping</span>
                            <span class="font-semibold text-gray-900">RM
                                {{ number_format($order->shipping_fee ?? 0, 2) }}</span>
                        </div>
                        <div class="pt-3 border-t border-gray-200 flex justify-between items-baseline">
                            <span class="text-base font-bold text-gray-900">Grand Total</span>
                            <span class="text-2xl font-black text-[#8f6a10]">RM
                                {{ number_format($order->total ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>

        {{-- RIGHT COLUMN: Actions --}}
        <div class="space-y-6">

            {{-- Status Update Card --}}
            <div class="bg-white border border-[#D4AF37]/20 rounded-2xl shadow-[0_18px_40px_rgba(0,0,0,0.04)] p-6">
                <h3 class="font-bold text-gray-900 mb-1">Process Order</h3>
                <p class="text-sm text-gray-400 mb-5">Update the lifecycle stage of this order.</p>

                <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-gray-400 block mb-2">
                            Order Status
                        </label>
                        <select id="order-status-select" name="status"
                            class="w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 text-sm font-semibold">
                            @foreach (['pending', 'paid', 'processing', 'shipped', 'completed', 'cancelled', 'failed'] as $s)
                                <option value="{{ $s }}" @selected($order->status === $s)>{{ strtoupper($s) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 物流信息区块 --}}
                    <div id="shipping-fields" class="space-y-3 mt-3 hidden">
                        <div class="flex items-center justify-between gap-2">
                            <h4 class="text-xs font-bold uppercase tracking-widest text-gray-400">
                                Shipping Information
                            </h4>
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-50 text-[10px] font-bold text-blue-700 border border-blue-200">
                                Required when SHIPPED
                            </span>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">
                                Courier / Shipping Provider
                            </label>
                            <input type="text" name="shipping_courier"
                                value="{{ old('shipping_courier', $order->shipping_courier) }}"
                                class="w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 text-sm"
                                placeholder="e.g. J&T, Ninja Van, PosLaju">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">
                                Tracking Number
                            </label>
                            <input type="text" name="tracking_number"
                                value="{{ old('tracking_number', $order->tracking_number) }}"
                                class="w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 text-sm"
                                placeholder="e.g. JV0123456789MY">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">
                                Shipped At
                            </label>
                            <input type="datetime-local" name="shipped_at"
                                value="{{ old('shipped_at', $order->shipped_at ? $order->shipped_at->format('Y-m-d\TH:i') : '') }}"
                                class="w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 text-sm">
                        </div>
                    </div>

                    <button
                        class="w-full py-3 rounded-xl bg-[#D4AF37] text-white font-bold text-sm hover:bg-[#c29c2f] transition-all shadow-lg shadow-[#D4AF37]/20 active:scale-[0.98]">
                        Update Progress
                    </button>
                </form>

            </div>

            {{-- Payment Metadata --}}
            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6">
                <h3 class="text-base font-bold tracking-widest text-black-400">Payment</h3>
                <p class="text-sm text-gray-400 mb-6">No display transaction id mean failed.</p>

                <div class="space-y-5">
                    {{-- Payment Method --}}
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500 font-medium">Method:</span>
                        <span class="text-sm font-bold text-gray-900">
                            {{ $order->payment_method_name ?? '—' }}
                        </span>
                    </div>

                    {{-- Payment Result (HitPay Webhook) --}}
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500 font-medium">Status (Gateway):</span>

                        <span
                            class="text-sm font-bold
                                @if ($order->payment_status === 'completed') text-green-700
                                @elseif ($order->payment_status === 'failed') text-red-600
                                @else text-gray-900 @endif">
                            {{ ucfirst($order->payment_status ?? '—') }}
                        </span>
                    </div>

                    {{-- Transaction ID (Copyable) --}}
                    @if ($order->payment_reference)
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500 font-medium">Transaction ID:</span>

                            <button onclick="navigator.clipboard.writeText('{{ $order->payment_reference }}')"
                                class="text-sm font-bold text-blue-600 hover:underline">
                                {{ $order->payment_reference }}
                            </button>
                        </div>
                    @endif

                    @if ($order->payment_receipt_path)
                        <div class="pt-4 border-t border-gray-200">
                            <label class="text-xs font-bold uppercase tracking-widest text-gray-400 block mb-3">Transaction
                                Proof</label>
                            <div class="flex flex-col gap-2">
                                <button onclick="document.getElementById('receiptModal').showModal()"
                                    class="flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-white border border-gray-300 text-sm font-bold text-gray-700 hover:bg-gray-50 transition shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    View Receipt
                                </button>
                                <a href="{{ asset('storage/' . $order->payment_receipt_path) }}" download
                                    class="flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-[#D4AF37]/10 border border-[#D4AF37]/30 text-sm font-bold text-[#8f6a10] hover:bg-[#D4AF37]/20 transition">

                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M12 3v12m0 0l3.75-3.75M12 15L8.25 11.25" />
                                    </svg>

                                    Download Proof
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Refined Modal Design --}}
    @if ($order->payment_receipt_path)
        <dialog id="receiptModal" class="rounded-2xl p-0 shadow-2xl backdrop:bg-black/60 border-none outline-none">
            <div class="flex flex-col max-w-2xl">
                <div class="px-6 py-4 border-b flex justify-between items-center bg-white">
                    <div class="font-bold text-gray-900">Payment Verification Receipt</div>
                    <button onclick="document.getElementById('receiptModal').close()"
                        class="p-2 hover:bg-gray-100 rounded-full transition text-gray-400 hover:text-gray-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-4 bg-gray-50">
                    <img src="{{ asset('storage/' . $order->payment_receipt_path) }}"
                        class="max-h-[75vh] w-auto rounded-lg shadow-inner">
                </div>
            </div>
        </dialog>
    @endif

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('order-status-select');
            const shippingFields = document.getElementById('shipping-fields');
            const courierInput = document.querySelector('input[name="shipping_courier"]');
            const trackingInput = document.querySelector('input[name="tracking_number"]');
            const shippedAtInput = document.querySelector('input[name="shipped_at"]');

            function toggleShippingFields() {
                const value = statusSelect.value;

                const needShipping = (value === 'shipped' || value === 'completed');

                if (needShipping) {
                    shippingFields.classList.remove('hidden');
                    courierInput?.setAttribute('required', 'required');
                    trackingInput?.setAttribute('required', 'required');
                } else {
                    shippingFields.classList.add('hidden');
                    courierInput?.removeAttribute('required');
                    trackingInput?.removeAttribute('required');
                }
            }

            statusSelect.addEventListener('change', toggleShippingFields);

            // 初次载入时根据当前状态决定要不要显示（例如订单已经是 shipped）
            toggleShippingFields();
        });
    </script>
@endpush
