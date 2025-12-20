<x-app-layout>
    <div class="bg-[#F5F5F7] min-h-screen">
        <div class="max-w-7xl5 mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

            {{-- Header + 小标题 --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900">Shop</h1>
                    <p class="text-sm text-gray-500">
                        Browse BRIF Shop products and find what you need.
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
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="px-4 py-2 rounded-xl bg-[#D4AF37]/90 text-white text-sm font-semibold hover:bg-[#b8942f] transition">
                            Apply
                        </button>

                        <a href="{{ route('shop.index') }}"
                            class="px-3 py-2 rounded-xl border border-gray-200 text-xs sm:text-sm text-gray-600 hover:bg-gray-50 transition">
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
                            <div class="relative aspect-[4/3] bg-gray-100 overflow-hidden">
                                @if ($product->image ?? false)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-xs text-gray-400">
                                        Image coming soon
                                    </div>
                                @endif

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

                                <div class="mt-2 flex items-center justify-between">
                                    <p class="text-sm font-semibold text-[#8f6a10]">
                                        RM {{ number_format($product->price, 2) }}
                                    </p>

                                    <button type="button"
                                        class="inline-flex items-center rounded-full border border-gray-200 px-2.5 py-1 text-[11px] font-medium text-gray-700 group-hover:border-[#D4AF37]/70 group-hover:text-[#8f6a10]">
                                        View details
                                    </button>
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
