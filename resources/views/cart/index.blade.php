<x-app-layout>
    <div class="bg-[#f7f7f9] min-h-screen py-6 sm:py-10">
        <div class="max-w-7xl5 mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <nav class="flex items-center uppercase space-x-2 text-sm text-gray-500 mb-6">
                <a href="{{ route('home') }}" class="hover:text-[#8f6a10] transition-colors">Home</a>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-900 font-medium">Shopping Cart</span>
            </nav>

            @if ($items->isEmpty())
                {{-- Empty State --}}
                <section
                    class="bg-white rounded-3xl border border-gray-100 shadow-sm p-12 text-center w-full ">
                    <div class="flex flex-col items-center">
                        <div class="w-20 h-20 rounded-full bg-[#D4AF37]/10 flex items-center justify-center mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-[#8f6a10]" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">Your cart is empty</h1>
                        <p class="text-gray-500 mb-8  mx-auto">
                            It looks like you haven't added anything yet. Discover our latest arrivals and find your
                            favorites!
                        </p>
                        <a href="{{ route('shop.index') }}"
                            class="inline-flex items-center px-8 py-3 rounded-full bg-[#D4AF37] text-white font-bold shadow-lg shadow-[#D4AF37]/30 hover:bg-[#b8962d] transition-all transform hover:-translate-y-0.5">
                            Start Shopping
                        </a>
                    </div>
                </section>
            @else
                <div class="lg:grid lg:grid-cols-12 lg:gap-10 items-start">

                    {{-- Left: Item List --}}
                    <div class="lg:col-span-8">
                        {{-- <div class="flex items-center justify-between mb-6">
                            <h1 class="text-2xl font-bold text-gray-900">Shopping Cart <span
                                    class="text-gray-400 font-normal">({{ $items->count() }})</span></h1>
                        </div> --}}

                        <div class="space-y-4">
                            @foreach ($items as $item)
                                @php $p = $item->product; @endphp
                                <div
                                    class="group relative bg-white border border-gray-100 rounded-2xl p-4 sm:p-5 flex gap-4 sm:gap-6 hover:shadow-md transition-shadow">

                                    {{-- Product Image --}}
                                    <div
                                        class="w-24 h-24 sm:w-32 sm:h-32 bg-gray-50 rounded-xl overflow-hidden flex-shrink-0 border border-gray-50">
                                        @if ($p?->image)
                                            <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        @else
                                            <div
                                                class="w-full h-full flex items-center justify-center text-xs text-gray-400">
                                                No image</div>
                                        @endif
                                    </div>

                                    {{-- Details --}}
                                    <div class="flex-1 flex flex-col justify-between min-w-0">
                                        <div class="flex justify-between items-start gap-2">
                                            <div>
                                                <p
                                                    class="text-[10px] uppercase tracking-widest text-[#8f6a10] font-bold mb-1">
                                                    {{ $p->category->name ?? 'Collection' }}
                                                </p>
                                                <h2
                                                    class="text-base sm:text-lg font-bold text-gray-900 leading-tight line-clamp-1">
                                                    {{ $p->name }}
                                                </h2>
                                                @if ($item->variant_label)
                                                    <span
                                                        class="inline-block mt-1 px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-md">
                                                        {{ $item->variant_label }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-right">
                                                <p class="text-lg font-bold text-gray-900">RM
                                                    {{ number_format($item->unit_price * $item->qty, 2) }}</p>
                                                <p class="text-xs text-gray-400 italic">RM
                                                    {{ number_format($item->unit_price, 2) }} / pcs</p>
                                            </div>
                                        </div>

                                        {{-- Controls --}}
                                        <div class="flex items-center justify-between mt-4">
                                            <form method="POST" action="{{ route('cart.update', $item) }}"
                                                class="flex items-center bg-gray-50 rounded-lg p-1 border border-gray-200">
                                                @csrf @method('PATCH')
                                                <button type="submit" name="action" value="decrease"
                                                    class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-[#8f6a10] hover:bg-white rounded-md transition disabled:opacity-30"
                                                    {{ $item->qty <= 1 ? 'disabled' : '' }}>
                                                    <span class="text-xl leading-none">−</span>
                                                </button>
                                                <span
                                                    class="w-10 text-center font-bold text-gray-900">{{ $item->qty }}</span>
                                                <button type="submit" name="action" value="increase"
                                                    class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-[#8f6a10] hover:bg-white rounded-md transition">
                                                    <span class="text-xl leading-none">+</span>
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('cart.remove', $item) }}">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-all group/del">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Right: Summary --}}
                    <aside class="mt-10 lg:mt-0 lg:col-span-4 sticky top-28">
                        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                            <div class="p-6 bg-[#F9F4E5]/50 border-b border-[#E5D9B6]/30">
                                <h2 class="text-xl font-bold text-gray-900">Order Summary</h2>
                            </div>

                            <div class="p-6 space-y-4">
                                <div class="flex justify-between text-gray-600">
                                    <span>Subtotal</span>
                                    <span class="font-bold text-gray-900">RM {{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Shipping</span>
                                    <span class="text-sm italic">Calculated at next step</span>
                                </div>

                                <div class="border-t border-gray-100 pt-4 mt-4">
                                    <div class="flex justify-between items-end mb-6">
                                        <span class="text-base font-bold text-gray-900">Total</span>
                                        <div class="text-right">
                                            <span class="text-2xl font-black text-[#8f6a10]">RM
                                                {{ number_format($subtotal, 2) }}</span>
                                        </div>
                                    </div>

                                    <div class="space-y-3">
                                        <a href="{{ route('checkout.index') }}"
                                            class="w-full flex items-center justify-center px-6 py-4 rounded-full bg-[#D4AF37] text-white text-lg font-bold shadow-lg shadow-[#D4AF37]/20 hover:bg-[#b8962d] transition-all">
                                            Checkout Now
                                        </a>
                                        <a href="{{ route('shop.index') }}"
                                            class="w-full flex items-center justify-center px-6 py-3 rounded-full border-2 border-gray-100 text-gray-600 font-bold hover:bg-gray-50 transition-all">
                                            Continue Shopping
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="px-6 py-4 bg-gray-50 flex items-center justify-center gap-4">
                                <span
                                    class="text-[10px] text-gray-400 font-bold uppercase tracking-widest flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor"
                                        stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 15a2 2 0 100-4 2 2 0 000 4z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M5 10h14v8a2 2 0 01-2 2H7a2 2 0 01-2-2v-8z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8 10V7a4 4 0 118 0v3" />
                                    </svg>
                                    Secure SSL Checkout
                                </span>
                            </div>
                        </div>
                    </aside>

                </div>
            @endif

        </div>
    </div>
</x-app-layout>
