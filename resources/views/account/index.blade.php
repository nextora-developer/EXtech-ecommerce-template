<x-app-layout>
    <div class="bg-[#f7f7f9] min-h-screen py-10">
        <div class="max-w-7xl5 mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-xs font-medium uppercase tracking-widest text-gray-400 mb-8">
                <a href="{{ route('home') }}" class="hover:text-[#8f6a10] transition-colors">Home</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span class="text-gray-900">Account Overview</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                {{-- Left sidebar --}}
                <aside class="lg:col-span-1">
                    @include('account.partials.sidebar')
                </aside>

                {{-- Right Content --}}
                <main class="lg:col-span-3 space-y-8">

                    {{-- Header Section --}}
                    <section class="relative overflow-hidden bg-white rounded-3xl border border-gray-100 shadow-sm p-8">
                        {{-- Subtle Decorative Background Element --}}
                        <div
                            class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-[#F9F4E5] rounded-full opacity-50 blur-3xl">
                        </div>

                        <div class="relative flex flex-col md:flex-row md:items-end justify-between gap-6">
                            <div>
                                <h1 class="text-3xl font-black text-gray-900 leading-tight">
                                    Welcome back, <br class="sm:hidden"> {{ explode(' ', $user->name)[0] }}
                                </h1>
                                <p class="text-sm text-gray-500 mt-2 max-w-md">
                                    From your dashboard you can view your recent orders, manage your shipping addresses
                                    and edit your password and account details.
                                </p>
                            </div>

                            {{-- Stats Grid --}}
                            <div class="flex items-center gap-3">
                                @foreach ([['label' => 'Orders', 'value' => $stats['orders']], ['label' => 'Wishlist', 'value' => $stats['favorites']], ['label' => 'Addresses', 'value' => $stats['addresses']]] as $stat)
                                    <div
                                        class="px-5 py-3 rounded-2xl bg-gray-50 border border-gray-100 text-center min-w-[100px]">
                                        <div class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">
                                            {{ $stat['label'] }}</div>
                                        <div class="text-xl font-black text-gray-900">{{ $stat['value'] }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>

                    {{-- Latest Orders Section --}}
                    <section>
                        <div class="flex items-center justify-between mb-5 px-2">
                            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <span class="w-1.5 h-6 bg-[#D4AF37] rounded-full"></span>
                                Recent Orders
                            </h2>

                            <a href="{{ route('account.orders.index') }}"
                                class="text-sm font-bold text-[#8f6a10] hover:text-[#D4AF37] flex items-center gap-1 group transition-colors">
                                View History
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </a>
                        </div>

                        <div class="space-y-3">
                            @forelse ($latestOrders as $order)
                                <a href="{{ route('account.orders.show', $order) }}"
                                    class="group block bg-white rounded-2xl border border-gray-100 p-4 hover:border-[#D4AF37]/40 hover:shadow-md hover:shadow-orange-100/20 transition-all duration-300">

                                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                        {{-- Order Image --}}
                                        @php
                                            $firstItem = $order->items->first();
                                            $thumb =
                                                $firstItem && $firstItem->product && $firstItem->product->image
                                                    ? asset('storage/' . $firstItem->product->image)
                                                    : null;
                                        @endphp

                                        <div
                                            class="w-16 h-16 rounded-xl overflow-hidden bg-gray-50 border border-gray-100 flex-shrink-0">
                                            @if ($thumb)
                                                <img src="{{ $thumb }}"
                                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                                    alt="">
                                            @else
                                                <div
                                                    class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-300">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                            stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Order Main Info --}}
                                        <div class="flex-1">
                                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                                <span
                                                    class="text-sm font-bold text-gray-900">#{{ $order->order_no }}</span>
                                                <span
                                                    class="text-xs text-gray-400 font-medium">{{ $order->created_at->format('M d, Y') }}</span>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1 line-clamp-1">
                                                {{ $order->items->count() }}
                                                item{{ $order->items->count() > 1 ? 's' : '' }} • Delivering to
                                                {{ $order->shipping_city ?? 'Registered Address' }}
                                            </p>
                                        </div>

                                        {{-- Status & Total --}}
                                        <div
                                            class="flex items-center justify-between sm:justify-end gap-6 sm:text-right">
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
                                                class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-tighter border {{ $statusClasses[$order->status] ?? 'bg-gray-50' }}">
                                                {{ $order->status }}
                                            </span>

                                            <div class="text-sm font-black text-gray-900">
                                                RM {{ number_format($order->total, 2) }}
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
                        </div>
                    </section>
                </main>

            </div>
        </div>
    </div>
</x-app-layout>
