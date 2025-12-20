<x-app-layout>
    <div class="bg-[#f7f7f9] py-10">
        <div class="max-w-7xl5 mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <nav class="text-xs text-gray-500 mb-4">
                <a href="{{ route('home') }}" class="hover:text-[#8f6a10]">Home</a>
                <span class="mx-1">/</span>
                <span class="text-gray-400">Cart</span>
            </nav>

            {{-- Card --}}
            <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8 text-center">
                <div class="flex flex-col items-center gap-3">

                    {{-- 小图标 --}}
                    <div class="w-12 h-12 rounded-full bg-[#D4AF37]/10 flex items-center justify-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#8f6a10]" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 3h2l.4 2M7 13h10l2-8H5.4M7 13L5.4 5M7 13l-2 8m12-8l2 8M9 21a1 1 0 100-2 1 1 0 000 2zm8 1a1 1 0 100-2 1 1 0 000 2z" />
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
        </div>
    </div>
</x-app-layout>
