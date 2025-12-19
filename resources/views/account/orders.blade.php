<x-app-layout>
    <div class="bg-[#f7f7f9] py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <nav class="text-xs text-gray-500 mb-4">
                <a href="{{ route('home') }}" class="hover:text-[#8f6a10]">Home</a>
                <span class="mx-1">/</span>
                <span class="text-gray-400">Order</span>
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
                        <div class="flex items-center gap-6 border-b border-gray-200 pb-2 text-sm">
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

                                <a href="{{ route('account.orders', ['status' => $key]) }}"
                                    class="{{ $active ? 'text-[#8f6a10] font-semibold' : 'text-gray-500 hover:text-gray-800' }}">
                                    {{ $label }} ({{ $counts[$key] ?? 0 }})
                                </a>
                            @endforeach
                        </div>

                        {{-- Search --}}
                        <form method="GET" action="{{ route('account.orders') }}"
                            class="mt-4 flex items-center gap-3">
                            <input type="hidden" name="status" value="{{ $status }}">

                            <input type="text" name="order_number" value="{{ request('order_number') }}"
                                placeholder="Order number"
                                class="flex-1 rounded-full border border-gray-200 px-5 py-2.5 text-sm text-gray-800 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">

                            <button type="submit"
                                class="px-6 py-2.5 rounded-full bg-[#D4AF37] text-white text-sm font-semibold shadow hover:brightness-110 transition">
                                Search
                            </button>

                            {{-- Reset --}}
                            <a href="{{ route('account.orders', ['status' => $status]) }}"
                                class="px-6 py-2.5 rounded-full bg-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-300 transition">
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
                            <div
                                class="rounded-xl border border-gray-200 px-4 py-3 mb-3 bg-gray-50 flex justify-between">
                                <div>
                                    <div class="font-medium text-[#8f6a10]">
                                        {{ $order->order_no }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $order->created_at->format('d M Y, H:i') }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold text-[#0A0A0C]">
                                        RM {{ number_format($order->total, 2) }}
                                    </div>
                                    <div class="text-xs text-[#D4AF37]">
                                        {{ $order->status }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No orders yet.</p>
                        @endforelse
                    </section>

                </main>
            </div>
        </div>
    </div>
</x-app-layout>
