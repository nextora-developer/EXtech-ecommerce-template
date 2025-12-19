{{-- resources/views/account/orders/show.blade.php --}}
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

                {{-- Include Sidebar --}}
                <aside class="lg:col-span-1">
                    @include('account.partials.sidebar')
                </aside>

                {{-- Right Content --}}
                <main class="lg:col-span-3 space-y-5">
                    <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            
                            <div>
                                <h1 class="text-lg font-semibold text-[#0A0A0C]">
                                    Order #{{ $order->order_no }}
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
                                class="px-3 py-1 rounded-full text-xs font-medium {{ $colors[$order->status] ?? 'bg-gray-100 text-gray-500' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6 mb-6">
                            {{-- 左边：客户 & 地址 --}}
                            <div class="space-y-3 text-sm">
                                <h2 class="font-semibold text-[#0A0A0C] text-sm">Customer</h2>
                                <p class="text-gray-700">
                                    {{ $order->customer_name }}<br>
                                    <span class="text-gray-500 text-xs">{{ $order->customer_phone }}</span>
                                </p>

                                <h2 class="font-semibold text-[#0A0A0C] text-sm mt-4">Shipping Address</h2>
                                <p class="text-gray-700 text-sm">
                                    {{ $order->address_line1 }}<br>
                                    @if ($order->address_line2)
                                        {{ $order->address_line2 }}<br>
                                    @endif
                                    {{ $order->postcode }} {{ $order->city }}<br>
                                    {{ $order->state }}
                                </p>
                            </div>

                            {{-- 右边：金额 --}}
                            <div class="bg-[#FFF9E6] border border-[#D4AF37]/30 rounded-2xl p-4 text-sm">
                                <h2 class="font-semibold text-[#0A0A0C] text-sm mb-3">Summary</h2>

                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-gray-500">Subtotal</span>
                                    <span class="font-medium text-gray-900">
                                        RM {{ number_format($order->subtotal, 2) }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-gray-500">Shipping</span>
                                    <span class="font-medium text-gray-900">
                                        RM {{ number_format($order->shipping_fee, 2) }}
                                    </span>
                                </div>

                                <div class="h-px bg-[#D4AF37]/20 my-3"></div>

                                <div class="flex items-center justify-between">
                                    <span class="font-semibold text-[#0A0A0C]">Total</span>
                                    <span class="font-semibold text-lg text-[#0A0A0C]">
                                        RM {{ number_format($order->total, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Item 列表 --}}
                        <h2 class="font-semibold text-[#0A0A0C] text-sm mb-3">Items</h2>

                        <div class="border border-gray-200 rounded-2xl overflow-hidden">
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
                                            <td class="px-4 py-2 text-gray-900">
                                                {{ $item->product_name }}
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
                    </section>
                </main>
            </div>
        </div>
    </div>
</x-app-layout>
