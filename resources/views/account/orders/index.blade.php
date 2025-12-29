<x-app-layout>
    <div class="bg-[#f7f7f9] py-8">
        <div class="max-w-7xl5 mx-auto sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <nav class="text-sm text-gray-500 mb-4">
                <a href="{{ route('home') }}" class="hover:text-[#8f6a10]">Home</a>
                <span class="mx-1">/</span>
                <span class="text-gray-400">Orders</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

                {{-- Include Sidebar --}}
                <aside class="lg:col-span-1">
                    @include('account.partials.sidebar')
                </aside>

                {{-- Right Content --}}
                <main class="lg:col-span-3 space-y-5">

                    {{-- Card 1: Filter Tabs + Search --}}
                    <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">

                        @php
                            $status = request('status', 'all');
                        @endphp

                        {{-- Tabs --}}
                        <div class="flex items-center gap-6 border-b border-gray-200 pb-2 text-base">
                            @php
                                $status = request('status', 'all');
                                $tabs = [
                                    'all' => 'All',
                                    'pending' => 'Pending',
                                    'paid' => 'Paid',
                                    'processing' => 'Processing',
                                    'shipped' => 'Shipped',
                                    'completed' => 'Completed',
                                    'cancelled' => 'Cancelled',
                                ];
                            @endphp

                            @php
                                $counts = [
                                    'all' => $allOrders->count(),
                                    'pending' => $allOrders->where('status', 'pending')->count(),
                                    'paid' => $allOrders->where('status', 'paid')->count(),
                                    'processing' => $allOrders->where('status', 'processing')->count(),
                                    'shipped' => $allOrders->where('status', 'shipped')->count(),
                                    'completed' => $allOrders->where('status', 'completed')->count(),
                                    'cancelled' => $allOrders->where('status', 'cancelled')->count(),
                                ];
                            @endphp

                            @foreach ($tabs as $key => $label)
                                @php
                                    $active = $status === $key;
                                @endphp

                                <a href="{{ route('account.orders.index', ['status' => $key]) }}"
                                    class="{{ $active ? 'text-[#8f6a10] font-semibold' : 'text-gray-500 hover:text-gray-800' }}">
                                    {{ $label }} ({{ $counts[$key] ?? 0 }})
                                </a>
                            @endforeach
                        </div>

                        {{-- Search --}}
                        <form method="GET" action="{{ route('account.orders.index') }}"
                            class="mt-4 flex items-center gap-3">

                            <input type="hidden" name="status" value="{{ $status }}">

                            <input type="text" name="order_no" value="{{ request('order_no') }}"
                                placeholder="Order number"
                                class="flex-1 rounded-full border border-gray-200 px-5 py-3 text-base text-gray-800 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">

                            <button type="submit"
                                class="px-6 py-3 rounded-full bg-[#D4AF37] text-white text-base font-semibold shadow hover:brightness-110 transition">
                                Search
                            </button>

                            {{-- Reset --}}
                            <a href="{{ route('account.orders.index', ['status' => $status]) }}"
                                class="px-6 py-3 rounded-full bg-gray-200 text-gray-700 text-base font-medium hover:bg-gray-300 transition">
                                Reset
                            </a>
                        </form>

                    </section>

                    {{-- Card 2: Orders List --}}
                    <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-[#0A0A0C]">
                                My Orders
                            </h2>
                        </div>

                        @forelse ($orders as $order)
                            <a href="{{ route('account.orders.show', $order) }}"
                                class="rounded-xl border border-gray-200 px-4 py-3 mb-3 bg-gray-50
              flex items-center gap-4 hover:bg-[#FFF9E6] hover:border-[#D4AF37]/50
              transition cursor-pointer">

                                {{-- 封面商品缩略图 --}}
                                @php
                                    $firstItem = $order->items->first();
                                    $thumb = null;

                                    if ($firstItem && $firstItem->product && $firstItem->product->image) {
                                        $thumb = asset('storage/' . $firstItem->product->image);
                                    }
                                @endphp

                                <div
                                    class="w-14 h-14 rounded-xl overflow-hidden border border-gray-200 bg-gray-100 flex-shrink-0">
                                    @if ($thumb)
                                        <img src="{{ $thumb }}" class="w-full h-full object-cover"
                                            alt="">
                                    @else
                                        <div
                                            class="w-full h-full flex items-center justify-center text-gray-400 text-xs">
                                            —
                                        </div>
                                    @endif
                                </div>

                                {{-- 订单信息 --}}
                                <div class="flex-1 flex justify-between items-center">

                                    <div>
                                        <span class="font-medium text-[#8f6a10] hover:text-[#D4AF37]">
                                            {{ $order->order_no }}
                                        </span>

                                        <div class="text-sm text-gray-500 mt-1">
                                            {{ $order->created_at->format('d M Y, H:i') }}
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-4 font-medium text-[#0A0A0C]">

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
                                            class="px-2 py-1 rounded-full text-sm font-medium
                             {{ $colors[$order->status] ?? 'bg-gray-100 text-gray-500' }}">
                                            {{ ucfirst($order->status) }}
                                        </span>

                                        <span class="text-base">
                                            RM {{ number_format($order->total, 2) }}
                                        </span>
                                    </div>

                                </div>
                            </a>
                        @empty
                            <p class="text-base text-gray-500">No orders yet.</p>
                        @endforelse


                        <div class="mt-4">
                            {{ $orders->links() }}
                        </div>

                    </section>
                </main>

            </div>
        </div>
    </div>
</x-app-layout>
