<x-app-layout>
    <div class="bg-[#f7f7f9] py-8">
        <div class="max-w-7xl5 mx-auto sm:px-6 lg:px-8">

            {{-- 面包屑 --}}
            <div class="text-sm text-gray-500 mb-4">
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
                                <h1 class="text-2xl font-semibold text-[#0A0A0C] flex items-center gap-2">
                                    Order <span class="text-[#8f6a10]">#{{ $order->order_no }}</span>
                                </h1>
                                <p class="text-sm text-gray-500 mt-1">
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

                            <div class="flex items-center gap-3">

                                {{-- Status Badge --}}
                                <span
                                    class="px-3 py-1 rounded-full text-sm font-medium shadow-sm
               {{ $colors[$order->status] ?? 'bg-gray-100 text-gray-500' }}">
                                    {{ ucfirst($order->status) }}
                                </span>

                                {{-- Order Received Button --}}
                                @if ($order->status === 'shipped')
                                    <form method="POST" action="{{ route('account.orders.complete', $order) }}">
                                        @csrf
                                        <button
                                            class="inline-flex items-center px-4 py-2 rounded-xl
                       bg-emerald-600 text-white text-sm font-semibold
                       hover:bg-emerald-700 transition">
                                            ✓ Order Received
                                        </button>
                                    </form>
                                @endif

                            </div>


                        </div>

                        {{-- Info blocks --}}
                        <div class="grid md:grid-cols-2 gap-6 mt-8">

                            {{-- 左侧：Customer + Shipping --}}
                            <div class="space-y-4">

                                {{-- Customer --}}
                                <div class="rounded-2xl border border-gray-200 bg-white/70 p-5 shadow-sm">
                                    <h2 class="text-xs font-semibold text-gray-500 tracking-[0.16em] uppercase">
                                        Customer
                                    </h2>

                                    <div class="mt-3 space-y-1">
                                        <p class="text-gray-900 font-medium">
                                            {{ $order->customer_name }}
                                        </p>

                                        <p class="text-gray-600 text-sm">
                                            {{ $order->customer_phone }}
                                        </p>

                                        <p class="text-gray-600 text-sm">
                                            {{ $order->customer_email }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Shipping Address --}}
                                <div class="rounded-2xl border border-gray-200 bg-white/70 p-5 shadow-sm">
                                    <h2 class="text-xs font-semibold text-gray-500 tracking-[0.16em] uppercase">
                                        Shipping Address
                                    </h2>

                                    <div class="mt-3 text-gray-900 leading-relaxed text-sm">
                                        {{ $order->address_line1 }}<br>

                                        @if ($order->address_line2)
                                            {{ $order->address_line2 }}<br>
                                        @endif

                                        {{ $order->postcode }} {{ $order->city }}<br>
                                        {{ $order->state }}
                                    </div>
                                </div>

                            </div>

                            {{-- 右侧：Order Summary + Payment --}}
                            <div class="bg-[#FFF9E6] border border-[#D4AF37]/30 rounded-2xl p-5 text-base shadow-sm">
                                <h2 class="font-semibold text-[#0A0A0C] text-base mb-4">Order Summary</h2>

                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between text-gray-600">
                                        <span>Subtotal</span>
                                        <span>RM {{ number_format($order->subtotal, 2) }}</span>
                                    </div>

                                    <div class="flex justify-between text-gray-600">
                                        <span>Shipping Fee</span>
                                        <span>RM {{ number_format($order->shipping_fee, 2) }}</span>
                                    </div>
                                </div>

                                <div class="h-px bg-[#D4AF37]/20 my-4"></div>

                                <div class="flex justify-between items-baseline">
                                    <span class="text-sm font-semibold text-[#0A0A0C]">Total</span>
                                    <span class="text-2xl font-semibold text-[#0A0A0C]">
                                        RM {{ number_format($order->total, 2) }}
                                    </span>
                                </div>

                                {{-- Payment info --}}
                                <div class="mt-5 pt-4 border-t border-[#D4AF37]/20 space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Payment Method</span>
                                        <span class="font-medium text-gray-900">
                                            {{ $order->payment_method_name }}
                                        </span>
                                    </div>

                                    @if ($order->payment_receipt_path)
                                        <div class="flex items-center gap-2 pt-1">
                                            {{-- 打开 modal --}}
                                            <button type="button" onclick="openReceiptModal({{ $order->id }})"
                                                class="inline-flex items-center px-3 py-1.5 rounded-lg border border-gray-300
                       bg-white/80 hover:bg-white text-xs font-medium text-gray-800">
                                                View Receipt
                                            </button>

                                            {{-- 直接下载 --}}
                                            <a href="{{ asset('storage/' . $order->payment_receipt_path) }}" download
                                                class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium
                       bg-[#D4AF37] text-white hover:bg-[#C49A2F]">
                                                Download
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>


                        {{-- Items --}}
                        <h2 class="font-semibold text-[#0A0A0C] text-base mt-8 mb-4">Items</h2>

                        <div class="border rounded-2xl overflow-hidden">
                            <table class="w-full text-base">
                                <thead class="bg-gray-50 text-sm text-gray-500">
                                    <tr>
                                        <th class="text-left px-4 py-3">Product</th>
                                        <th class="text-right px-4 py-3">Qty</th>
                                        <th class="text-right px-4 py-3">Unit Price</th>
                                        <th class="text-right px-4 py-3">Subtotal</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-100 text-base">
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td class="px-4 py-3 text-gray-900 flex items-center gap-3">
                                                @if ($item->product?->image)
                                                    <img src="{{ asset('storage/' . $item->product->image) }}"
                                                        class="w-12 h-12 rounded object-cover">
                                                @endif
                                                <div>
                                                    {{ $item->product_name }}
                                                    @if ($item->variant_label)
                                                        <div class="text-sm text-gray-500">
                                                            {{ $item->variant_label }}
                                                        </div>
                                                    @endif

                                                </div>
                                            </td>

                                            <td class="px-4 py-3 text-right text-gray-700">
                                                {{ $item->qty }}
                                            </td>

                                            <td class="px-4 py-3 text-right text-gray-700">
                                                RM {{ number_format($item->unit_price, 2) }}
                                            </td>

                                            <td class="px-4 py-3 text-right font-semibold text-gray-900">
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

    @if ($order->payment_receipt_path)
        <div id="receiptModal-{{ $order->id }}" class="fixed inset-0 z-50 hidden bg-black/50">
            {{-- 点击背景关闭 --}}
            <div class="flex items-center justify-center min-h-screen"
                onclick="closeReceiptModal({{ $order->id }})">
                {{-- 内容卡片，阻止冒泡 --}}
                <div class="bg-white rounded-2xl shadow-xl max-w-xl w-[90%] overflow-hidden"
                    onclick="event.stopPropagation()">
                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-900">
                            Payment Receipt
                        </h3>
                        <button type="button" class="text-gray-400 hover:text-gray-600"
                            onclick="closeReceiptModal({{ $order->id }})">
                            ✕
                        </button>
                    </div>

                    <div class="p-4">
                        <img src="{{ asset('storage/' . $order->payment_receipt_path) }}" alt="Payment receipt"
                            class="max-h-[70vh] w-auto mx-auto rounded-lg shadow-sm">
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        function openReceiptModal(orderId) {
            const el = document.getElementById('receiptModal-' + orderId);
            if (el) {
                el.classList.remove('hidden');
            }
        }

        function closeReceiptModal(orderId) {
            const el = document.getElementById('receiptModal-' + orderId);
            if (el) {
                el.classList.add('hidden');
            }
        }
    </script>


</x-app-layout>
