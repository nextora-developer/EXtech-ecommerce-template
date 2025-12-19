<x-app-layout>
    <div class="bg-[#f7f7f9] py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- 面包屑 --}}
            <div class="text-xs text-gray-500 mb-4">
                <a href="{{ route('account.index') }}" class="hover:text-[#8f6a10]">Home</a>
                <span class="mx-1">/</span>
                <a href="{{ route('account.orders.index') }}" class="hover:text-[#8f6a10]">Orders</a>
                <span class="mx-1">/</span>
                <span>{{ $order->order_no }}</span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

                <aside class="lg:col-span-1">
                    @include('account.partials.sidebar')
                </aside>

                <main class="lg:col-span-3 space-y-5">

                    {{-- Header --}}
                    <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                        <div class="flex items-start justify-between">

                            <div>
                                <h1 class="text-xl font-semibold text-[#0A0A0C] flex items-center gap-2">
                                    Order <span class="text-[#8f6a10]">#{{ $order->order_no }}</span>
                                </h1>
                                <p class="text-xs text-gray-500 mt-1">
                                    Placed on {{ $order->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>

                            @php
                                $colors = [
                                    'pending' => 'bg-amber-100 text-[#8f6a10]',
                                    'paid' => 'bg-green-100 text-green-700',
                                    'processing' => 'bg-blue-100 text-blue-700',
                                    'shipped' => 'bg-indigo-100 text-indigo-700',
                                    'completed' => 'bg-emerald-100 text-emerald-700',
                                    'cancelled' => 'bg-red-100 text-red-600',
                                ];
                            @endphp

                            <span
                                class="px-3 py-1 rounded-full text-xs font-medium shadow-sm {{ $colors[$order->status] ?? 'bg-gray-100 text-gray-500' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                

                        {{-- Info blocks --}}
                        <div class="grid md:grid-cols-2 gap-6 mt-6">

                            {{-- Customer / Address --}}
                            <div class="space-y-3 text-sm">
                                <h2 class="font-semibold text-[#0A0A0C] text-sm">Customer</h2>

                                <p class="text-gray-700">
                                    {{ $order->customer_name }}<br>
                                    <span class="text-gray-500 text-xs">{{ $order->customer_phone }}</span>
                                </p>

                                <h2 class="font-semibold text-[#0A0A0C] text-sm mt-4">Shipping Address</h2>
                                <p class="text-gray-700 text-sm leading-5">
                                    {{ $order->address_line1 }}<br>
                                    @if($order->address_line2) {{ $order->address_line2 }}<br> @endif
                                    {{ $order->postcode }} {{ $order->city }}<br>
                                    {{ $order->state }}
                                </p>
                            </div>

                            {{-- Summary --}}
                            <div class="bg-[#FFF9E6] border border-[#D4AF37]/30 rounded-2xl p-4 text-sm shadow-sm">
                                <h2 class="font-semibold text-[#0A0A0C] text-sm mb-3">Order Summary</h2>

                                <div class="flex justify-between mb-1.5 text-gray-600">
                                    <span>Subtotal</span>
                                    <span>RM {{ number_format($order->subtotal, 2) }}</span>
                                </div>

                                <div class="flex justify-between mb-1.5 text-gray-600">
                                    <span>Shipping Fee</span>
                                    <span>RM {{ number_format($order->shipping_fee, 2) }}</span>
                                </div>

                                <div class="h-px bg-[#D4AF37]/20 my-3"></div>

                                <div class="flex justify-between text-[#0A0A0C] font-semibold">
                                    <span>Total</span>
                                    <span class="text-lg">RM {{ number_format($order->total, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Items --}}
                        <h2 class="font-semibold text-[#0A0A0C] text-sm mt-6 mb-3">Items</h2>

                        <div class="border rounded-2xl overflow-hidden">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 text-xs text-gray-500">
                                    <tr>
                                        <th class="text-left px-4 py-2">Product</th>
                                        <th class="text-right px-4 py-2">Qty</th>
                                        <th class="text-right px-4 py-2">Unit Price</th>
                                        <th class="text-right px-4 py-2">Subtotal</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td class="px-4 py-2 text-gray-900 flex items-center gap-2">
                                                @if($item->product?->image)
                                                    <img src="{{ asset('storage/'.$item->product->image) }}"
                                                         class="w-10 h-10 rounded object-cover">
                                                @endif
                                                <div>
                                                    {{ $item->product_name }}
                                                    @if($item->variant)
                                                        <div class="text-xs text-gray-500">{{ $item->variant }}</div>
                                                    @endif
                                                </div>
                                            </td>

                                            <td class="px-4 py-2 text-right text-gray-700">
                                                {{ $item->qty }}
                                            </td>

                                            <td class="px-4 py-2 text-right text-gray-700">
                                                RM {{ number_format($item->unit_price, 2) }}
                                            </td>

                                            <td class="px-4 py-2 text-right font-medium text-gray-900">
                                                RM {{ number_format($item->unit_price * $item->qty, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Action buttons --}}
                        {{-- <div class="flex flex-wrap gap-3 mt-6">
                            <a href="{{ route('account.orders.index') }}"
                               class="px-4 py-2 text-sm border rounded-lg hover:bg-gray-50">
                                Back to Orders
                            </a>

                            <button class="px-4 py-2 text-sm rounded-lg bg-[#D4AF37] text-white hover:bg-[#c09c2f]">
                                Download Invoice
                            </button>
                        </div> --}}

                    </section>
                </main>
            </div>
        </div>
    </div>
</x-app-layout>
