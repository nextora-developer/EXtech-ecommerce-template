<x-app-layout>
    <div class="bg-[#f7f7f9] py-10">
        <div class="max-w-7xl5 mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <nav class="text-sm text-gray-500 mb-4">
                <a href="{{ route('home') }}" class="hover:text-[#8f6a10]">Home</a>
                <span class="mx-1">/</span>
                <span class="text-gray-400">Cart</span>
            </nav>

            @if ($items->isEmpty())
                {{-- 空购物车 --}}
                <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8 text-center">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-[#D4AF37]/10 flex items-center justify-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#8f6a10]" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                            </svg>
                        </div>

                        <h1 class="text-lg font-semibold text-[#0A0A0C]">
                            Your cart is empty
                        </h1>

                        <p class="text-sm text-gray-500 max-w-md">
                            Looks like you haven&apos;t added any items yet.
                            Browse our products and add your favourites to the cart.
                        </p>

                        <div class="mt-4">
                            <a href="{{ route('shop.index') }}"
                                class="inline-flex items-center px-6 py-2.5 rounded-full bg-[#D4AF37] text-white text-sm font-semibold shadow hover:brightness-110 transition">
                                Start shopping
                            </a>
                        </div>
                    </div>
                </section>
            @else
                {{-- 有商品的 Cart --}}
                <section
                    class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8 flex flex-col gap-6 lg:grid lg:grid-cols-3 lg:gap-8">

                    {{-- 左：商品列表 --}}
                    <div class="lg:col-span-2 space-y-4">
                        <h1 class="text-lg font-semibold text-[#0A0A0C] mb-2">
                            Shopping Cart
                        </h1>

                        @foreach ($items as $item)
                            @php
                                $p = $item->product;
                            @endphp

                            <div
                                class="flex gap-4 border border-gray-100 rounded-2xl px-3 py-3 sm:px-4 sm:py-4 items-start">
                                {{-- 商品图片 --}}
                                <div
                                    class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl bg-gray-100 overflow-hidden flex-shrink-0">
                                    @if ($p?->image)
                                        <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div
                                            class="w-full h-full flex items-center justify-center text-[11px] text-gray-400">
                                            No image
                                        </div>
                                    @endif
                                </div>

                                {{-- 名称 + 价格 + 控制 --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between gap-3">
                                        <div>
                                            <p class="text-xs uppercase tracking-[0.16em] text-gray-400 mb-1">
                                                {{ $p->category->name ?? 'Product' }}
                                            </p>
                                            <h2 class="text-sm font-semibold text-gray-900 line-clamp-2">
                                                {{ $p->name }}
                                            </h2>

                                            {{-- 🔹 在这里显示 variant --}}
                                            @if ($item->variant_label)
                                                <p class="text-[11px] text-gray-500 mt-1">
                                                    {{ $item->variant_label }}
                                                </p>
                                            @endif
                                        </div>

                                        <div class="text-right">
                                            <p class="text-sm font-semibold text-[#8f6a10]">
                                                RM {{ number_format($item->unit_price, 2) }}
                                            </p>
                                            <p class="text-xs text-gray-400">
                                                Line total:
                                                <span class="font-medium text-gray-700">
                                                    RM {{ number_format($item->unit_price * $item->qty, 2) }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>

                                    {{-- 数量 + 删除 --}}
                                    <div class="mt-3 flex items-center justify-between gap-3">
                                        {{-- 数量调整 --}}
                                        <form method="POST" action="{{ route('cart.update', $item) }}"
                                            class="inline-flex items-center">
                                            @csrf
                                            @method('PATCH')

                                            <div
                                                class="inline-flex items-center rounded-full border border-gray-300 bg-white px-3 py-1.5 shadow-sm gap-3">

                                                {{-- - --}}
                                                <button type="submit" name="action" value="decrease"
                                                    class="text-lg font-medium text-gray-500 hover:text-[#8f6a10] transition">
                                                    −
                                                </button>

                                                {{-- 数量 --}}
                                                <span
                                                    class="min-w-[24px] text-center text-base font-semibold text-gray-800 select-none">
                                                    {{ $item->qty }}
                                                </span>

                                                {{-- + --}}
                                                <button type="submit" name="action" value="increase"
                                                    class="text-lg font-medium text-gray-500 hover:text-[#8f6a10] transition">
                                                    +
                                                </button>
                                            </div>
                                        </form>



                                        {{-- 删除 --}}
                                        <form method="POST" action="{{ route('cart.remove', $item) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-xs text-gray-400 hover:text-red-500 flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- 右：Summary --}}
                    <aside class="bg-[#F9F4E5] rounded-2xl border border-[#E5D9B6] p-5 h-max">
                        <h2 class="text-sm font-semibold text-[#0A0A0C] mb-4">
                            Order Summary
                        </h2>

                        <dl class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Subtotal</dt>
                                <dd class="font-semibold text-gray-900">
                                    RM {{ number_format($subtotal, 2) }}
                                </dd>
                            </div>
                            {{-- 将来可以加 shipping / discount --}}
                            {{-- <div class="flex justify-between">
                                <dt class="text-gray-500">Shipping</dt>
                                <dd class="text-gray-700">Calculated at checkout</dd>
                            </div> --}}
                        </dl>

                        <div class="border-t border-[#E5D9B6] my-4"></div>

                        <div class="flex justify-between items-center mb-4 text-sm">
                            <span class="font-semibold text-gray-900">Total</span>
                            <span class="text-lg font-semibold text-[#8f6a10]">
                                RM {{ number_format($subtotal, 2) }}
                            </span>
                        </div>

                        <a href="{{ route('cart.index') }}"
                            class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-full bg-[#D4AF37] text-white text-sm font-semibold shadow hover:brightness-110 transition">
                            Proceed to Checkout
                        </a>

                        <p class="mt-3 text-[11px] text-gray-500">
                            Secure checkout · All prices in RM
                        </p>
                    </aside>
                </section>
            @endif

        </div>
    </div>
</x-app-layout>
