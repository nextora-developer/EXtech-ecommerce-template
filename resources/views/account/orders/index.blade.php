<x-app-layout>
    <div class="bg-[#f7f7f9] min-h-screen py-10">
        <div class="max-w-7xl5 mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-xs font-medium uppercase tracking-widest text-gray-400 mb-8">
                <a href="{{ route('home') }}" class="hover:text-[#8f6a10] transition-colors">Home</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span class="text-gray-900">Orders</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                {{-- Left Sidebar --}}
                <aside class="lg:col-span-1">
                    @include('account.partials.sidebar')
                </aside>

                {{-- Right Content --}}
                <main class="lg:col-span-3 space-y-6">

                    {{-- Header & Filters --}}
                    <section class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-gray-50">
                            <h1 class="text-2xl font-black text-gray-900">Order History</h1>
                            <p class="text-sm text-gray-500 mt-1">Track, manage and view details of your past purchases.
                            </p>
                        </div>

                        {{-- Horizontal Scrollable Tabs --}}
                        <div class="px-6 py-2 bg-gray-50/50 flex items-center gap-2 overflow-x-auto no-scrollbar">
                            @php
                                $status = request('status', 'all');
                                $tabs = [
                                    'all' => 'All Orders',
                                    'pending' => 'Pending',
                                    'paid' => 'Paid',
                                    'processing' => 'Processing',
                                    'shipped' => 'Shipped',
                                    'completed' => 'Completed',
                                    'cancelled' => 'Cancelled',
                                    'failed' => 'Failed', // 👈 Added
                                ];
                            @endphp

                            @foreach ($tabs as $key => $label)
                                @php $isActive = $status === $key; @endphp
                                <a href="{{ route('account.orders.index', ['status' => $key]) }}"
                                    class="whitespace-nowrap px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-full transition-all duration-300
                                    {{ $isActive
                                        ? 'bg-[#D4AF37] text-white shadow-md shadow-orange-900/20'
                                        : 'text-gray-400 hover:text-gray-900 hover:bg-gray-100' }}">
                                    {{ $label }}
                                    <span
                                        class="ml-1 opacity-60 text-xs">{{ $allOrders->where('status', $key === 'all' ? '!=' : '==', $key)->count() }}</span>
                                </a>
                            @endforeach
                        </div>

                        {{-- Search Bar --}}
                        <div class="p-6">
                            <form method="GET" action="{{ route('account.orders.index') }}" class="relative max-w-md">
                                <input type="hidden" name="status" value="{{ $status }}">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <input type="text" name="order_no" value="{{ request('order_no') }}"
                                    placeholder="Search by order ID..."
                                    class="block w-full pl-11 pr-24 py-3 bg-gray-50 border-none rounded-2xl text-sm text-gray-900 focus:ring-2 focus:ring-[#D4AF37]/20 transition-all">

                                <div class="absolute inset-y-1.5 right-1.5 flex gap-1">
                                    @if (request('order_no'))
                                        <a href="{{ route('account.orders.index', ['status' => $status]) }}"
                                            class="px-3 flex items-center text-xs font-bold text-gray-400 hover:text-gray-600">Reset</a>
                                    @endif
                                    <button type="submit"
                                        class="px-4 bg-[#D4AF37] text-white rounded-xl text-xs font-bold hover:bg-[#EBCB5A] transition-colors">
                                        Find
                                    </button>
                                </div>
                            </form>
                        </div>
                    </section>

                    {{-- Orders List --}}
                    <div class="space-y-4">
                        @forelse ($orders as $order)
                            <a href="{{ route('account.orders.show', $order) }}"
                                class="group block bg-white rounded-3xl border border-gray-100 p-5 hover:border-[#D4AF37]/40 hover:shadow-xl hover:shadow-orange-100/30 transition-all duration-500">

                                <div class="flex flex-col md:flex-row md:items-center gap-6">
                                    {{-- Thumbnail Stack --}}
                                    <div class="relative flex-shrink-0">
                                        @php
                                            $firstItem = $order->items->first();
                                            $thumb =
                                                $firstItem && $firstItem->product && $firstItem->product->image
                                                    ? asset('storage/' . $firstItem->product->image)
                                                    : null;
                                        @endphp
                                        <div
                                            class="w-20 h-20 rounded-2xl overflow-hidden bg-gray-50 border border-gray-100 shadow-sm relative z-10">
                                            @if ($thumb)
                                                <img src="{{ $thumb }}"
                                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                            @else
                                                <div
                                                    class="w-full h-full flex items-center justify-center text-gray-300">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                            stroke-width="1" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        {{-- Visual Stack Effect for multiple items --}}
                                        @if ($order->items->count() > 1)
                                            <div
                                                class="absolute top-1 left-1 w-20 h-20 bg-gray-100 border border-gray-200 rounded-2xl -z-10 translate-x-1 -translate-y-1">
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Order Details --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-3 mb-1">
                                            <span
                                                class="text-sm font-black text-gray-900 group-hover:text-[#8f6a10] transition-colors">#{{ $order->order_no }}</span>
                                            <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                            <span
                                                class="text-xs font-medium text-gray-400 uppercase tracking-tighter">{{ $order->created_at->format('d M Y') }}</span>
                                        </div>
                                        <p class="text-xs text-gray-500 line-clamp-1 mb-3">
                                            Purchased {{ $order->items->count() }}
                                            item{{ $order->items->count() > 1 ? 's' : '' }} •
                                            Delivering to <span
                                                class="text-gray-700 font-medium">{{ $order->shipping_city ?? 'Registered Address' }}</span>
                                        </p>

                                        {{-- Ghost Status Badges --}}
                                        @php
                                            $statusClasses = [
                                                'pending' => 'bg-amber-50 text-amber-700 border-amber-100',
                                                'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                                'processing' => 'bg-blue-50 text-blue-700 border-blue-100',
                                                'shipped' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                                'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                                'cancelled' => 'bg-red-50 text-red-700 border-red-100',
                                                'failed' => 'bg-rose-50 text-rose-700 border-rose-100',
                                            ];
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest border {{ $statusClasses[$order->status] ?? 'bg-gray-50 border-gray-100' }}">
                                            {{ $order->status }}
                                        </span>
                                    </div>

                                    {{-- Price & Action --}}
                                    <div
                                        class="flex md:flex-col items-center md:items-end justify-between gap-2 border-t md:border-t-0 pt-4 md:pt-0 border-gray-50">
                                        <div class="text-lg font-black text-gray-900">
                                            <span
                                                class="text-xs font-normal text-gray-400 mr-1">RM</span>{{ number_format($order->total, 2) }}
                                        </div>
                                        <div
                                            class="text-[10px] font-bold text-[#8f6a10] uppercase tracking-widest flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                            View Details
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path d="M14 5l7 7-7 7M3 12h18" stroke-width="3" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="bg-white rounded-3xl border border-dashed border-gray-200 p-12 text-center">
                                <div
                                    class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 text-gray-300 mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <h3 class="text-gray-900 font-bold">No orders found</h3>
                                <p class="text-sm text-gray-500 mt-1">When you buy something, it will appear here.
                                </p>
                                <a href="{{ route('shop.index') }}"
                                    class="mt-6 inline-flex items-center px-6 py-2.5
                                                bg-black text-white text-sm font-bold rounded-xl
                                                transition-all duration-300 ease-out
                                                hover:bg-black hover:text-white
                                                hover:-translate-y-0.5 hover:scale-[1.03]
                                                hover:shadow-xl hover:shadow-gray/30">
                                    Start Shopping
                                </a>
                            </div>
                        @endforelse

                        <div class="mt-8">
                            {{ $orders->links() }}
                        </div>
                    </div>
                </main>

            </div>
        </div>
    </div>
</x-app-layout>
