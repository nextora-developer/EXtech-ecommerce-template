<x-app-layout>
    <div class="bg-[#f7f7f9] py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <nav class="text-xs text-gray-500 mb-4">
                <a href="{{ route('home') }}" class="hover:text-[#8f6a10]">Home</a>
                <span class="mx-1">/</span>
                <span class="text-gray-400">Account</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

                {{-- 左边 sidebar：lg 占 1 栏 --}}
                <aside class="lg:col-span-1">
                    @include('account.partials.sidebar')
                </aside>

                {{-- 右边内容：lg 占 3 栏 --}}
                <main class="lg:col-span-3 space-y-5">

                    {{-- 顶部欢迎 + 统计 --}}
                    <section
                        class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h1 class="text-xl font-semibold text-gray-900">
                                Hi, {{ $user->name }}
                            </h1>
                            <p class="text-sm text-gray-500 mt-1">
                                Manage your orders, account details and more.
                            </p>
                        </div>

                        <div class="flex gap-3">
                            <div
                                class="px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-center min-w-[90px]">
                                <div class="text-xs text-gray-500 mb-1">Orders</div>
                                <div class="text-lg font-semibold text-gray-900">
                                    {{ $stats['orders'] }}
                                </div>
                            </div>
                            <div
                                class="px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-center min-w-[90px]">
                                <div class="text-xs text-gray-500 mb-1">Favorites</div>
                                <div class="text-lg font-semibold text-gray-900">
                                    {{ $stats['favorites'] }}
                                </div>
                            </div>
                            <div
                                class="px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-center min-w-[90px]">
                                <div class="text-xs text-gray-500 mb-1">Addresses</div>
                                <div class="text-lg font-semibold text-gray-900">
                                    {{ $stats['addresses'] }}
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- 最近订单预览 --}}
                    <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-base font-semibold text-[#0A0A0C]">
                                Latest Orders
                            </h2>

                            <a href="{{ route('account.orders.index') }}"
                                class="text-base font-medium text-[#8f6a10] hover:text-[#D4AF37]">
                                View all →
                            </a>
                        </div>

                        @if ($latestOrders->count())
                            @foreach ($latestOrders as $order)
                                <a href="{{ route('account.orders.show', $order) }}"
                                    class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 flex items-center justify-between text-sm mb-2 hover:bg-[#FFF9E6] hover:border-[#D4AF37]/50 transition cursor-pointer">

                                    <div>
                                        <span class="font-medium text-[#8f6a10] hover:text-[#D4AF37]">
                                            {{ $order->order_no }}
                                        </span>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $order->created_at->format('d M Y, H:i') }}
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-5 font-medium text-[#0A0A0C]">
                                        {{-- Status Badge --}}
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
                                            class="px-2 py-1 rounded-full text-xs font-medium {{ $colors[$order->status] ?? 'bg-gray-100 text-gray-500' }}">
                                            {{ ucfirst($order->status) }}
                                        </span>

                                        {{-- Total --}}
                                        <span>
                                            RM {{ number_format($order->total, 2) }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        @else
                            <p class="text-sm text-gray-500">
                                You don’t have any orders yet.
                            </p>
                        @endif
                </main>
            </div>
        </div>
    </div>
</x-app-layout>
