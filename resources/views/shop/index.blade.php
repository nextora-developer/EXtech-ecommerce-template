<x-app-layout>
    <div class="bg-[#F5F5F7] min-h-screen">
        <div class="max-w-7xl5 mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

            {{-- Header + 小标题 --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900">Shop</h1>
                    <p class="text-sm text-gray-500">
                        Browse Shop products and find what you need.
                    </p>
                </div>

                {{-- 总数 --}}
                <div class="text-xs sm:text-sm text-gray-500">
                    Showing <span class="font-semibold text-gray-800">{{ $products->total() }}</span> items
                </div>
            </div>

            {{-- Filter Bar --}}
            <form method="GET" action="{{ route('shop.index') }}"
                class="mb-6 bg-white border border-[#D4AF37]/18 rounded-2xl px-4 py-3 sm:px-5 sm:py-4 shadow-[0_10px_30px_rgba(0,0,0,0.06)]">

                <div class="flex flex-col md:flex-row md:items-center gap-3 md:gap-4">

                    {{-- Search --}}
                    <div class="flex-1">
                        <label class="block text-[11px] uppercase tracking-wide text-gray-400 mb-1">
                            Search
                        </label>
                        <div class="relative">
                            <input type="text" name="q" value="{{ request('q') }}"
                                placeholder="Search products..."
                                class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-sm text-gray-700
                                        focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 focus:outline-none">
                            <span class="absolute right-3 top-2.5 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z" />
                                </svg>
                            </span>
                        </div>
                    </div>

                    {{-- Category --}}
                    <div class="w-full md:w-56">
                        <label class="block text-[11px] uppercase tracking-wide text-gray-400 mb-1">
                            Category
                        </label>
                        <select name="category"
                            class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm text-gray-700
                                   focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                            <option value="">All categories</option>
                            @isset($categories)
                                @foreach ($categories as $category)
                                    <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                    </div>

                    {{-- Sort --}}
                    <div class="w-full md:w-48">
                        <label class="block text-[11px] uppercase tracking-wide text-gray-400 mb-1">
                            Sort by
                        </label>
                        <select name="sort"
                            class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm text-gray-700
                                   focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                            <option value="">Default</option>
                            <option value="latest" @selected(request('sort') === 'latest')>Latest</option>
                            <option value="price_asc" @selected(request('sort') === 'price_asc')>Price: Low → High</option>
                            <option value="price_desc" @selected(request('sort') === 'price_desc')>Price: High → Low</option>
                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-end gap-2 mt-4">
                        <button type="submit"
                            class="px-4 py-2 rounded-xl bg-[#D4AF37]/90 text-white text-sm font-semibold hover:bg-[#b8942f] transition">
                            Apply
                        </button>

                        <a href="{{ route('shop.index') }}"
                            class="px-4 py-2 rounded-xl border border-gray-200 text-sm sm:text-sm text-gray-600 hover:bg-gray-50 transition">
                            Reset
                        </a>
                    </div>

                </div>
            </form>

            {{-- Product Grid --}}
            @if ($products->count())
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 sm:gap-6">
                    @foreach ($products as $product)
                        <a href="{{ route('shop.show', $product->slug) }}"
                            class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-[#D4AF37]/60 transition overflow-hidden flex flex-col">

                            {{-- Product image：跟 home 一样 aspect-[4/3] --}}
                            <div class="relative aspect-square bg-gray-100 overflow-hidden">
                                @if ($product->image ?? false)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-xs text-gray-400">
                                        Image coming soon
                                    </div>
                                @endif

                                {{-- ❤️ Favorite button --}}
                                @auth
                                    @php
                                        $isFavorited = auth()->user()->favorites->contains('product_id', $product->id);
                                    @endphp

                                    <form
                                        action="{{ $isFavorited ? route('account.favorites.destroy', $product) : route('account.favorites.store', $product) }}"
                                        method="POST" class="absolute top-2 right-2 z-10">
                                        @csrf
                                        @if ($isFavorited)
                                            @method('DELETE')
                                        @endif

                                        <button type="submit"
                                            class="w-8 h-8 flex items-center justify-center rounded-full bg-white/80 backdrop-blur
                    hover:bg-white text-[#8f6a10] shadow-sm transition">

                                            {{-- If favorited show solid heart --}}
                                            @if ($isFavorited)
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="#D4AF37" viewBox="0 0 24 24"
                                                    class="h-5 w-5">
                                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42
                                            4.42 3 7.5 3c1.74 0 3.41.81 4.5
                                            2.09C13.09 3.81 14.76 3 16.5
                                            3 19.58 3 22 5.42 22 8.5c0
                                            3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                                </svg>
                                            @else
                                                {{-- empty heart --}}
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#8f6a10"
                                                    stroke-width="1.8" viewBox="0 0 24 24" class="h-5 w-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.5c0-2.8-2.2-5-5-5-1.9
                                            0-3.6 1-4.5 2.5C10.6 4.5 8.9 3.5
                                            7 3.5 4.2 3.5 2 5.7 2 8.5c0 5.2
                                            5.5 8.9 9.8 12.7.1.1.3.1.4
                                            0C15.5 17.4 21 13.7 21 8.5z" />
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                @endauth

                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/30 via-black/0 to-transparent opacity-0 group-hover:opacity-100 transition">
                                </div>
                            </div>

                            {{-- Content：跟 home 统一 --}}
                            <div class="flex-1 flex flex-col px-3.5 py-3">
                                <p class="text-xs uppercase tracking-[0.18em] text-gray-400 mb-1">
                                    {{ $product->category->name ?? 'Product' }}
                                </p>

                                <h3 class="text-sm font-semibold text-gray-900 line-clamp-2">
                                    {{ $product->name }}
                                </h3>

                                <div class="mt-3 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                    <p class="text-sm font-semibold text-[#8f6a10]">
                                        @if ($product->has_variants && $product->variants->count())
                                            @php
                                                $variantPrices = $product->variants->whereNotNull('price');
                                                $min = $variantPrices->min('price');
                                                $max = $variantPrices->max('price');
                                            @endphp

                                            @if ($min == $max)
                                                RM {{ number_format($min, 2) }}
                                            @else
                                                <span class="text-xs font-normal text-gray-400 mr-1">From</span>
                                                RM {{ number_format($min, 2) }}
                                            @endif
                                        @else
                                            RM {{ number_format($product->price ?? 0, 2) }}
                                        @endif
                                    </p>

                                    <span
                                        class="inline-flex items-center justify-center rounded-full border border-gray-200 px-3 py-1.5 text-[11px] font-medium text-gray-700
                                                w-full sm:w-auto
                                                group-hover:border-[#D4AF37]/70 group-hover:text-[#8f6a10] transition">
                                        View details
                                    </span>
                                </div>

                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $products->withQueryString()->links() }}
                </div>
            @else
                <div
                    class="mt-8 flex flex-col items-center justify-center border border-dashed border-gray-300 rounded-2xl bg-white py-10">
                    <p class="text-sm text-gray-500">
                        No products found. Try adjusting your filters.
                    </p>
                    <a href="{{ route('shop.index') }}"
                        class="mt-3 inline-flex items-center px-4 py-2 rounded-full text-xs font-medium bg-gray-900 text-white hover:bg-black">
                        Back to shop
                    </a>
                </div>
            @endif


        </div>
    </div>
</x-app-layout>
