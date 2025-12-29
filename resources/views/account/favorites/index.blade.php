<x-app-layout>
    <div class="bg-[#f7f7f9] py-8">
        <div class="max-w-7xl5 mx-auto sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <nav class="text-sm text-gray-500 mb-4">
                <a href="{{ route('home') }}" class="hover:text-[#8f6a10]">Home</a>
                <span class="mx-1">/</span>
                <a href="{{ route('account.index') }}" class="hover:text-[#8f6a10]">Account</a>
                <span class="mx-1">/</span>
                <span class="text-gray-400">Favorites</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

                {{-- 左侧 sidebar --}}
                <aside class="lg:col-span-1">
                    @include('account.partials.sidebar')
                </aside>

                {{-- 右侧内容 --}}
                <main class="lg:col-span-3 space-y-5">

                    {{-- 标题 --}}
                    <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-2xl font-semibold text-gray-900">
                                    My Favorites
                                </h1>
                                <p class="text-base text-gray-500 mt-1">
                                    Products you’ve added to your favorites.
                                </p>
                            </div>

                            @if ($favorites->count())
                                <div class="text-sm text-gray-500">
                                    Total:
                                    <span class="font-semibold text-[#0A0A0C]">
                                        {{ $favorites->total() }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </section>

                    {{-- 收藏列表 --}}
                    <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                        @if ($favorites->isEmpty())
                            <div
                                class="rounded-2xl border border-dashed border-gray-200 p-8 text-center text-base text-gray-500">
                                You haven’t added any favorite products yet.
                            </div>
                        @else
                            {{-- 用和 featured 一样的 card 样式 --}}
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                                @foreach ($favorites as $favorite)
                                    @php
                                        $product = $favorite->product;
                                    @endphp

                                    @if ($product)
                                        <a href="{{ route('shop.show', $product->slug) }}"
                                            class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-[#D4AF37]/60 transition overflow-hidden flex flex-col">

                                            {{-- Product image --}}
                                            <div class="relative aspect-square bg-gray-100 overflow-hidden">
                                                @if ($product->image ?? false)
                                                    <img src="{{ asset('storage/' . $product->image) }}"
                                                        alt="{{ $product->name }}"
                                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                                @else
                                                    <div
                                                        class="w-full h-full flex items-center justify-center text-xs text-gray-400">
                                                        Image coming soon
                                                    </div>
                                                @endif

                                                {{-- ❤️ Favorite button（这里默认都是已收藏，可以点掉） --}}
                                                @auth
                                                    <form action="{{ route('account.favorites.destroy', $product) }}"
                                                        method="POST" class="absolute top-2 right-2 z-10">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit"
                                                            class="w-8 h-8 flex items-center justify-center rounded-full bg-white/80 backdrop-blur
                                                                hover:bg-white text-[#8f6a10] shadow-sm transition">
                                                            {{-- 固定实心心形，因为在收藏页 --}}
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="#D4AF37"
                                                                viewBox="0 0 24 24" class="h-5 w-5">
                                                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42
                                                                        4.42 3 7.5 3c1.74 0 3.41.81 4.5
                                                                        2.09C13.09 3.81 14.76 3 16.5
                                                                        3 19.58 3 22 5.42 22 8.5c0
                                                                        3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endauth

                                                <div
                                                    class="absolute inset-0 bg-gradient-to-t from-black/30 via-black/0 to-transparent opacity-0 group-hover:opacity-100 transition">
                                                </div>
                                            </div>

                                            {{-- Content --}}
                                            <div class="flex-1 flex flex-col px-3.5 py-3">
                                                <p class="text-xs uppercase tracking-[0.18em] text-gray-400 mb-1">
                                                    {{ $product->category->name ?? 'Product' }}
                                                </p>
                                                <h3 class="text-sm font-semibold text-gray-900 line-clamp-2">
                                                    {{ $product->name }}
                                                </h3>

                                                <div class="mt-2 flex items-center justify-between">
                                                    <p class="text-sm font-semibold text-[#8f6a10]">
                                                        @if ($product->has_variants && $product->variants->count())
                                                            @php
                                                                $variantPrices = $product->variants->whereNotNull(
                                                                    'price',
                                                                );
                                                                $min = $variantPrices->min('price');
                                                                $max = $variantPrices->max('price');
                                                            @endphp

                                                            @if ($min === null)
                                                                RM 0.00
                                                            @elseif ($min == $max)
                                                                RM {{ number_format($min, 2) }}
                                                            @else
                                                                RM {{ number_format($min, 2) }} –
                                                                {{ number_format($max, 2) }}
                                                            @endif
                                                        @else
                                                            RM {{ number_format($product->price ?? 0, 2) }}
                                                        @endif
                                                    </p>

                                                    <span
                                                        class="inline-flex items-center rounded-full border border-gray-200 px-2.5 py-1 text-[11px] font-medium text-gray-700 group-hover:border-[#D4AF37]/70 group-hover:text-[#8f6a10]">
                                                        View details
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    @endif
                                @endforeach
                            </div>

                            {{-- 分页 --}}
                            <div class="mt-6">
                                {{ $favorites->links() }}
                            </div>
                        @endif
                    </section>

                </main>

            </div>
        </div>
    </div>
</x-app-layout>
