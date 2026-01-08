<x-app-layout>
    <div class="bg-[#F8F8F9] min-h-screen font-sans antialiased text-gray-900 py-6 sm:py-10">
        <div class="max-w-7xl5 mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <nav class="flex items-center space-x-2 uppercase text-sm text-gray-500 mb-6">
                <a href="{{ route('shop.index') }}" class="hover:text-[#8f6a10] transition-colors">Shop</a>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-900 font-medium">{{ $product->name }}</span>
            </nav>

            {{-- 收藏状态计算 --}}
            @auth
                @php
                    $isFavorited = auth()->user()->favorites->contains('product_id', $product->id);
                @endphp
            @endauth

            {{-- Main Card --}}
            <div
                class="bg-white rounded-[2rem] border border-gray-100 shadow-[0_30px_60px_-15px_rgba(0,0,0,0.05)] overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-0">

                    {{-- Left: Image Gallery (Span 7) --}}
                    <div class="lg:col-span-7 p-4 sm:p-8 lg:p-10 bg-[#FCFCFD] border-r border-gray-50">
                        <div class="sticky top-10">

                            @php
                                $gallery = [];

                                // 多图优先
                                if (isset($product->images) && count($product->images)) {
                                    foreach ($product->images as $img) {
                                        $gallery[] = asset('storage/' . $img->path);
                                    }
                                }
                                // 没有多图时，才 fallback 用单图字段
                                elseif ($product->image ?? false) {
                                    $gallery[] = asset('storage/' . $product->image);
                                }

                                if (!count($gallery)) {
                                    $gallery[] = null;
                                }
                            @endphp

                            <div data-gallery class="relative group">

                                {{-- ❤️ Favorite Button --}}
                                @auth
                                    <form
                                        action="{{ $isFavorited ? route('account.favorites.destroy', $product) : route('account.favorites.store', $product) }}"
                                        method="POST" class="absolute top-4 right-4 z-30">
                                        @csrf
                                        @if ($isFavorited)
                                            @method('DELETE')
                                        @endif
                                        <button type="submit"
                                            class="w-11 h-11 flex items-center justify-center rounded-full bg-white/80 backdrop-blur-md shadow-sm border border-white hover:scale-110 transition-all duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                fill="{{ $isFavorited ? '#D4AF37' : 'none' }}"
                                                stroke="{{ $isFavorited ? '#D4AF37' : '#8f6a10' }}" stroke-width="1.5"
                                                viewBox="0 0 24 24" class="h-6 w-6">
                                                <path
                                                    d="M12 21.35l-1.45-1.32C5.4 15.36
                                                                                           2 12.28 2 8.5 2 5.42 4.42
                                                                                           3 7.5 3c1.74 0 3.41.81 4.5
                                                                                           2.09C13.09 3.81 14.76 3 16.5
                                                                                           3 19.58 3 22 5.42 22 8.5c0
                                                                                           3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                            </svg>
                                        </button>
                                    </form>
                                @endauth
                                <div data-gallery class="relative">
                                    {{-- Main Image Display（已缩小调整） --}}
                                    <div
                                        class="relative rounded-3xl overflow-hidden aspect-[4/3] max-h-[520px] bg-white shadow-inner mb-6">

                                        <div class="flex h-full transition-transform duration-700 ease-out"
                                            data-gallery-track>
                                            @foreach ($gallery as $url)
                                                <div class="w-full h-full shrink-0">
                                                    @if ($url)
                                                        <img src="{{ $url }}"
                                                            class="w-full h-full object-contain select-none"
                                                            alt="{{ $product->name }}">
                                                    @else
                                                        <div
                                                            class="w-full h-full flex flex-col items-center justify-center text-gray-300 bg-gray-50">
                                                            <svg class="w-10 h-10 mb-2 opacity-20" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path
                                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14" />
                                                            </svg>
                                                            <span class="text-xs tracking-widest uppercase">
                                                                Image Coming Soon
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- 左右按钮 --}}
                                    @if (count($gallery) > 1)
                                        <button type="button"
                                            class="hidden sm:flex absolute left-3 top-1/2 -translate-y-1/2
                   w-9 h-9 rounded-full bg-black/45 hover:bg-black/70
                   text-white items-center justify-center text-sm shadow
                   backdrop-blur-sm transition"
                                            data-gallery-prev>
                                            ‹
                                        </button>

                                        <button type="button"
                                            class="hidden sm:flex absolute right-3 top-1/2 -translate-y-1/2
                   w-9 h-9 rounded-full bg-black/45 hover:bg-black/70
                   text-white items-center justify-center text-sm shadow
                   backdrop-blur-sm transition"
                                            data-gallery-next>
                                            ›
                                        </button>
                                    @endif

                                    {{-- Thumbnails --}}
                                    @if (count($gallery) > 1)
                                        <div class="flex gap-4 justify-center" data-gallery-thumbs>
                                            @foreach ($gallery as $i => $url)
                                                <button type="button" data-thumb-index="{{ $i }}"
                                                    class="group relative w-20 h-20 rounded-2xl overflow-hidden border-2 transition-all {{ $loop->first ? 'border-[#D4AF37]' : 'border-transparent' }}">
                                                    @if ($url)
                                                        <img src="{{ $url }}"
                                                            class="w-full h-full object-cover">
                                                    @else
                                                        <div
                                                            class="w-full h-full flex items-center justify-center text-xs text-gray-400">
                                                            -
                                                        </div>
                                                    @endif
                                                    <div
                                                        class="absolute inset-0 bg-black/5 group-hover:bg-transparent transition">
                                                    </div>
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right: Product Details (Span 5) --}}
                    <div class="lg:col-span-5 p-6 sm:p-10 lg:p-12 flex flex-col">
                        <div class="flex-1">

                            {{-- Availability Badge --}}
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-bold uppercase tracking-wider border border-emerald-100 mb-6">
                                <span class="relative flex h-2 w-2">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                </span>
                                Ready Stock
                            </div>

                            {{-- Product Name --}}
                            <h1 class="text-3xl sm:text-3xl font-bold text-gray-900 tracking-tight leading-tight mb-4">
                                {{ $product->name }}
                            </h1>

                            {{-- Price Display（用你原本的变体逻辑） --}}
                            <div class="mt-2 mb-5 flex items-end gap-3">
                                <div class="text-3xl font-light text-[#8f6a10]" data-product-price>
                                    @if ($product->has_variants && $product->variants->count())
                                        @php
                                            $variantPrices = $product->variants->whereNotNull('price');
                                            $min = $variantPrices->min('price');
                                            $max = $variantPrices->max('price');
                                        @endphp

                                        @if ($min === null)
                                            <span class="font-semibold">RM 0.00</span>
                                        @elseif ($min == $max)
                                            <span class="font-semibold">RM {{ number_format($min, 2) }}</span>
                                        @else
                                            <span class="font-semibold">RM {{ number_format($min, 2) }}</span>
                                            <span class="text-gray-300 mx-1">–</span>
                                            <span class="font-semibold">RM {{ number_format($max, 2) }}</span>
                                        @endif
                                    @else
                                        <span class="font-semibold">
                                            RM {{ number_format($product->price ?? 0, 2) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Feature Bar / 信任条 --}}
                            <div class="grid grid-cols-2 gap-4 mb-6">

                                <div
                                    class="flex items-center gap-3 p-3 rounded-2xl
                                            bg-[#D4AF37]/10 border border-[#D4AF37]/20">
                                    <div class="p-2 bg-white rounded-xl shadow-sm">
                                        <svg class="w-4 h-4 text-[#8f6a10]" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <span class="text-[12px] font-medium text-[#8f6a10]">
                                        Ships in 1–3 working days
                                    </span>
                                </div>

                                <div
                                    class="flex items-center gap-3 p-3 rounded-2xl
                                            bg-[#D4AF37]/10 border border-[#D4AF37]/20">
                                    <div class="p-2 bg-white rounded-xl shadow-sm">
                                        <svg class="w-4 h-4 text-[#8f6a10]" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3" />
                                        </svg>
                                    </div>
                                    <span class="text-[12px] font-medium text-gray-600">
                                        Easy returns within 7 days
                                    </span>
                                </div>

                            </div>


                            {{-- Short Description --}}
                            <div class="prose prose-sm text-gray-500 leading-relaxed mb-8 max-w-xl">
                                @if ($product->short_description)
                                    <p>{{ $product->short_description }}</p>
                                @else
                                    <p>A premium selection crafted for quality and durability.</p>
                                @endif
                            </div>

                            <hr class="border-gray-100 mb-8">

                            {{-- Add to Cart + Variant Form（完整功能版） --}}
                            <form method="POST" action="{{ route('cart.add', $product) }}" class="space-y-8">
                                @csrf

                                {{-- Variants：用你原本的 variantMap 结构 --}}
                                @if ($product->has_variants && $product->options->count())
                                    @php
                                        $variantMap = $product->variants
                                            ->map(function ($variant) {
                                                return [
                                                    'id' => $variant->id,
                                                    'price' => $variant->price,
                                                    'stock' => $variant->stock,
                                                    'options' => $variant->options ?? [],
                                                ];
                                            })
                                            ->values();
                                    @endphp

                                    <div id="variant-picker" data-variants='@json($variantMap)'
                                        class="space-y-6">
                                        @foreach ($product->options as $option)
                                            <div>
                                                <label
                                                    class="block text-[11px] font-bold uppercase tracking-widest text-gray-400 mb-3">
                                                    Select {{ $option->label ?? $option->name }}
                                                </label>
                                                <div class="flex flex-wrap gap-2.5"
                                                    data-option-key="{{ $option->name }}">
                                                    @foreach ($option->values as $value)
                                                        <button type="button"
                                                            class="variant-pill h-11 px-6 rounded-xl border border-gray-200 text-sm font-medium transition-all hover:border-[#D4AF37] hover:bg-[#FDFBF7] active:scale-95"
                                                            data-option-key="{{ $option->name }}"
                                                            data-option-value="{{ $value->value }}">
                                                            {{ $value->value }}
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach

                                        <p class="text-sm text-[#B28A15]" id="variant-status">
                                            Please select all options first.
                                        </p>

                                        <input type="hidden" name="variant_id" id="variant_id">
                                    </div>
                                @endif

                                {{-- Quantity & Add to Cart --}}
                                <div class="flex flex-col sm:flex-row sm:items-end gap-4">
                                    <div class="w-32">
                                        <label
                                            class="block text-[11px] font-bold uppercase tracking-widest text-gray-400 mb-3">
                                            Quantity
                                        </label>
                                        <div
                                            class="flex items-center h-14 rounded-2xl border border-gray-200 bg-white overflow-hidden shadow-sm">
                                            <button type="button"
                                                class="flex-1 h-full text-gray-400 hover:text-gray-900 transition"
                                                onclick="const input = this.parentElement.querySelector('input'); if (parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;">
                                                –
                                            </button>
                                            <input type="number" name="quantity" value="1" min="1"
                                                class="w-10 text-center border-0 focus:ring-0 font-bold text-gray-900">
                                            <button type="button"
                                                class="flex-1 h-full text-gray-400 hover:text-gray-900 transition"
                                                onclick="const input = this.parentElement.querySelector('input'); input.value = parseInt(input.value || 1) + 1;">
                                                +
                                            </button>
                                        </div>
                                    </div>

                                    <div class="flex-1">
                                        <label
                                            class="block text-[11px] font-bold uppercase tracking-widest text-transparent mb-3">
                                            &nbsp;
                                        </label>
                                        <button type="submit"
                                            class="w-full h-14 bg-[#1a1a1a] text-white rounded-2xl font-bold text-sm uppercase tracking-widest hover:bg-black transition-all shadow-xl shadow-black/10 flex items-center justify-center gap-3 group">
                                            <span>Add to Cart</span>
                                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabs & Specs Section --}}
            <div class="mt-16">

                <div
                    class="bg-white rounded-[2rem] border border-gray-100 shadow-[0_18px_40px_rgba(0,0,0,0.04)] p-6 sm:p-8">

                    {{-- Tabs Header --}}
                    <div class="flex justify-center gap-12 border-b border-gray-100 mb-8">
                        <button onclick="switchTab('desc')" id="tab-btn-desc"
                            class="pb-4 text-sm font-bold uppercase tracking-widest border-b-2 border-[#D4AF37] text-gray-900">
                            Long Description
                        </button>
                        <button onclick="switchTab('info')" id="tab-btn-info"
                            class="pb-4 text-sm font-bold uppercase tracking-widest border-b-2 border-transparent text-gray-400 hover:text-gray-900 transition">
                            Additional Info
                        </button>
                    </div>

                    {{-- Description Tab --}}
                    <div id="tab-desc" class="prose prose-base max-w-none text-gray-600 leading-relaxed">
                        @if ($product->description)
                            {!! $product->description !!}
                        @else
                            <p class="text-gray-500 text-sm">No description for this product yet.</p>
                        @endif
                    </div>

                    {{-- Specs Tab --}}
                    <div id="tab-info" class="hidden">
                        @if (!empty($product->specs))
                            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm">

                                <div class="px-4 py-3 border-b bg-gray-50 rounded-t-2xl">
                                    <h4 class="font-semibold text-base text-gray-700">
                                        Product Specifications
                                    </h4>
                                </div>

                                <dl class="divide-y">
                                    @foreach ($product->specs as $row)
                                        <div
                                            class="grid grid-cols-[160px,1fr] gap-6 px-4 py-3 hover:bg-gray-50 transition">
                                            <dt class="text-sm font-medium text-gray-600">
                                                {{ $row['name'] ?? '-' }}
                                            </dt>
                                            <dd class="text-sm text-gray-800">
                                                {{ $row['value'] ?? '-' }}
                                            </dd>
                                        </div>
                                    @endforeach
                                </dl>

                            </div>
                        @else
                            <p class="text-center text-gray-400 py-10">
                                No additional info yet.
                            </p>
                        @endif
                    </div>


                </div>
            </div>


            {{-- Related Products --}}
            @if ($related->count())
                <div class="mt-12">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        Related Products
                    </h2>

                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 sm:gap-6">
                        @foreach ($related as $item)
                            @php
                                $itemFavorited = auth()->check()
                                    ? auth()->user()->favorites->contains('product_id', $item->id)
                                    : false;
                            @endphp

                            <a href="{{ route('shop.show', $item->slug) }}"
                                class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-[#D4AF37]/60 transition overflow-hidden flex flex-col">
                                {{-- Product image --}}
                                <div class="relative aspect-square bg-gray-100 overflow-hidden">
                                    @if ($item->image ?? false)
                                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div
                                            class="w-full h-full flex items-center justify-center text-xs text-gray-400">
                                            Image coming soon
                                        </div>
                                    @endif

                                    {{-- ❤️ Favorite --}}
                                    @auth
                                        <form
                                            action="{{ $itemFavorited ? route('account.favorites.destroy', $item) : route('account.favorites.store', $item) }}"
                                            method="POST" class="absolute top-2 right-2 z-20">
                                            @csrf
                                            @if ($itemFavorited)
                                                @method('DELETE')
                                            @endif

                                            <button type="submit"
                                                class="w-8 h-8 flex items-center justify-center rounded-full bg-white/80 backdrop-blur hover:bg-white shadow-sm">
                                                @if ($itemFavorited)
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="#D4AF37"
                                                        viewBox="0 0 24 24" class="h-5 w-5">
                                                        <path
                                                            d="M12 21.35l-1.45-1.32C5.4 15.36
                                                                                                   2 12.28 2 8.5 2 5.42 4.42
                                                                                                   3 7.5 3c1.74 0 3.41.81 4.5
                                                                                                   2.09C13.09 3.81 14.76 3 16.5
                                                                                                   3 19.58 3 22 5.42 22 8.5c0
                                                                                                   3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        stroke="#8f6a10" stroke-width="1.8" viewBox="0 0 24 24"
                                                        class="h-5 w-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M21 8.5c0-2.8-2.2-5-5-5-1.9
                                                                                                     0-3.6 1-4.5 2.5C10.6 4.5
                                                                                                     8.9 3.5 7 3.5 4.2 3.5 2
                                                                                                     5.7 2 8.5c0 5.2 5.5 8.9
                                                                                                     9.8 12.7.1.1.3.1.4 0C15.5
                                                                                                     17.4 21 13.7 21 8.5z" />
                                                    </svg>
                                                @endif
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
                                        {{ $item->category->name ?? 'Product' }}
                                    </p>

                                    <h3 class="text-sm font-semibold text-gray-900 line-clamp-2">
                                        {{ $item->name }}
                                    </h3>

                                    <div
                                        class="mt-3 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                        <p class="text-sm font-semibold text-[#8f6a10]">
                                            RM {{ number_format($item->price, 2) }}
                                        </p>

                                        <span
                                            class="inline-flex items-center justify-center rounded-full border border-gray-200
                                                    px-3 py-1.5 text-[11px] font-medium text-gray-700
                                                    w-full sm:w-auto
                                                    group-hover:border-[#D4AF37]/70 group-hover:text-[#8f6a10]
                                                    transition">
                                            View details
                                        </span>
                                    </div>

                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>


    <style>
        /* Chrome / Edge / Safari */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // ===== Variant logic =====
            // ===== Variant 组合选择逻辑（Color / Size 分组） =====
            const picker = document.getElementById('variant-picker');
            const variantInput = document.getElementById('variant_id');
            const statusEl = document.getElementById('variant-status');
            const priceEl = document.querySelector('[data-product-price]');
            const addBtn = document.querySelector('form[action*="cart.add"] button[type="submit"]');

            // 只要有 variant block 才跑
            if (!picker || !variantInput) return;

            // ---------- 1. 拿 variants，并处理 options 结构 ----------
            const raw = JSON.parse(picker.dataset.variants || '[]');

            // 兼容：options 可能是 object，也可能是 JSON 字符串
            function normalizeOptions(opts) {
                if (!opts) return {};
                if (typeof opts === 'string') {
                    try {
                        return JSON.parse(opts);
                    } catch (e) {
                        return {};
                    }
                }
                return opts;
            }

            // 把 {"label":"Color / Size","value":"red / M"} 转成：
            // optionsMap = { Color: "red", Size: "M" }
            function buildOptionsMap(variant) {
                const optRaw = normalizeOptions(variant.options);
                const labelStr = (optRaw.label || '').trim(); // "Color / Size"
                const valueStr = (optRaw.value || '').trim(); // "red / M"

                const labelParts = labelStr.split('/').map(s => s.trim()).filter(Boolean); // ["Color","Size"]
                const valueParts = valueStr.split('/').map(s => s.trim()).filter(Boolean); // ["red","M"]

                const map = {};
                labelParts.forEach((label, index) => {
                    if (!label) return;
                    const val = valueParts[index];
                    if (val === undefined) return;
                    map[label.toLowerCase()] = val; // key 统一用小写，方便比对
                });

                return map;
            }

            const variants = raw.map(v => ({
                ...v,
                _optionsMap: buildOptionsMap(v),
            }));

            const pills = Array.from(picker.querySelectorAll('.variant-pill'));
            const selections = {}; // 例如 { Color: "red", Size: "M" }

            // 一开始先禁止下单
            if (addBtn) {
                addBtn.disabled = true;
                addBtn.classList.add('opacity-60', 'cursor-not-allowed');
            }

            // 从 DOM 拿所有 option key：["Color","Size"]
            const optionKeys = Array.from(
                    picker.querySelectorAll('[data-option-key]')
                )
                .map(el => el.getAttribute('data-option-key'))
                .filter((v, i, self) => v && self.indexOf(v) === i);

            function refreshPills() {
                pills.forEach(btn => {
                    const key = btn.dataset.optionKey;
                    const value = btn.dataset.optionValue;
                    const active = selections[key] === value;

                    btn.classList.toggle('border-[#D4AF37]', active);
                    btn.classList.toggle('text-[#8f6a10]', active);
                    btn.classList.toggle('bg-[#F9F4E5]', active);
                    btn.classList.toggle('shadow-sm', active);

                    if (!active) {
                        btn.classList.add('border-gray-300', 'text-gray-800', 'bg-white');
                    } else {
                        btn.classList.remove('border-gray-300', 'text-gray-800', 'bg-white');
                    }
                });
            }

            function findVariant() {
                if (!variants.length) return null;

                // 必须所有 option 都选好
                const allSelected = optionKeys.every(k => selections[k]);
                if (!allSelected) return null;

                return variants.find(v => {
                    const map = v._optionsMap || {};
                    // 每个 key 用小写匹配
                    return optionKeys.every(key => {
                        const want = (selections[key] || '').toLowerCase();
                        const have = (map[key.toLowerCase()] || '').toLowerCase();
                        return want === have;
                    });
                }) || null;
            }

            function updateState() {
                refreshPills();
                const variant = findVariant();

                if (!variant) {
                    variantInput.value = '';

                    if (statusEl) {
                        const selectedCount = Object.keys(selections).length;
                        const allSelected = selectedCount === optionKeys.length;

                        if (allSelected) {
                            statusEl.textContent = '此选项组合暂不可用，请换一个组合试试。';
                            statusEl.classList.remove('text-gray-500');
                            statusEl.classList.add('text-red-500');
                        } else {
                            statusEl.textContent = 'Please select all options first.';
                            statusEl.classList.remove('text-red-500');
                            statusEl.classList.add('text-gray-500');
                        }
                    }

                    if (addBtn) {
                        addBtn.disabled = true;
                        addBtn.classList.add('opacity-60', 'cursor-not-allowed');
                    }
                    return;
                }

                // ✅ 找到正确的 variant
                variantInput.value = variant.id;

                if (statusEl) {
                    const parts = optionKeys.map(key => `${key}: ${selections[key]}`);
                    statusEl.textContent = 'Selected：' + parts.join(' • ');
                    statusEl.classList.remove('text-red-500');
                    statusEl.classList.add('text-gray-500');
                }

                if (priceEl && variant.price) {
                    priceEl.textContent = 'RM ' + Number(variant.price).toFixed(2);
                }

                if (addBtn) {
                    const outOfStock = variant.stock !== undefined && Number(variant.stock) <= 0;
                    addBtn.disabled = outOfStock;
                    addBtn.classList.toggle('opacity-60', outOfStock);
                    addBtn.classList.toggle('cursor-not-allowed', outOfStock);
                }
            }

            // 点击 Color / Size 的 pill 时更新 selections
            pills.forEach(btn => {
                btn.addEventListener('click', () => {
                    const key = btn.dataset.optionKey; // "Color" 或 "Size"
                    const value = btn.dataset.optionValue; // "red" 或 "M"

                    // 再点一次同一个可以取消选中，你不想取消就把这一段 if 删掉
                    if (selections[key] === value) {
                        delete selections[key];
                    } else {
                        selections[key] = value;
                    }

                    updateState();
                });
            });


        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const gallery = document.querySelector("[data-gallery]");
            if (!gallery) return;

            const track = gallery.querySelector("[data-gallery-track]");
            if (!track) return;

            const slides = Array.from(track.children);
            const prev = gallery.querySelector("[data-gallery-prev]");
            const next = gallery.querySelector("[data-gallery-next]");
            const thumbs = Array.from(gallery.querySelectorAll("[data-thumb-index]"));

            let index = 0;

            const go = (i) => {
                if (!slides.length) return;

                index = (i + slides.length) % slides.length;
                track.style.transform = `translateX(-${index * 100}%)`;

                thumbs.forEach((t, idx) => {
                    // ✅ 先清掉所有可能冲突的 border class
                    t.classList.remove("border-[#D4AF37]", "border-gray-200", "border-transparent");

                    // ✅ 再加回当前状态
                    if (idx === index) {
                        t.classList.add("border-[#D4AF37]");
                    } else {
                        t.classList.add("border-gray-200");
                    }
                });
            };

            go(0);

            prev?.addEventListener("click", () => go(index - 1));
            next?.addEventListener("click", () => go(index + 1));

            thumbs.forEach((t) => {
                t.addEventListener("click", () => {
                    const i = parseInt(t.getAttribute("data-thumb-index"), 10);
                    go(Number.isFinite(i) ? i : 0);
                });
            });

            // Swipe on mobile
            let sx = 0;
            track.addEventListener("touchstart", (e) => (sx = e.touches[0].clientX));
            track.addEventListener("touchend", (e) => {
                const dx = e.changedTouches[0].clientX - sx;
                if (dx > 50) go(index - 1);
                if (dx < -50) go(index + 1);
            });
        });
    </script>


    <script>
        function switchTab(tab) {
            const desc = document.getElementById('tab-desc');
            const info = document.getElementById('tab-info');
            const btnDesc = document.getElementById('tab-btn-desc');
            const btnInfo = document.getElementById('tab-btn-info');

            if (!desc || !info || !btnDesc || !btnInfo) return;

            if (tab === 'desc') {
                // 显示 Long Description
                desc.classList.remove('hidden');
                info.classList.add('hidden');

                // 按钮样式
                btnDesc.classList.add('text-gray-700', 'border-[#D4AF37]');
                btnDesc.classList.remove('text-gray-500', 'border-transparent');

                btnInfo.classList.add('text-gray-500', 'border-transparent');
                btnInfo.classList.remove('text-gray-700', 'border-[#D4AF37]');
            } else if (tab === 'info') {
                // 显示 Additional Info
                info.classList.remove('hidden');
                desc.classList.add('hidden');

                // 按钮样式
                btnInfo.classList.add('text-gray-700', 'border-[#D4AF37]');
                btnInfo.classList.remove('text-gray-500', 'border-transparent');

                btnDesc.classList.add('text-gray-500', 'border-transparent');
                btnDesc.classList.remove('text-gray-700', 'border-[#D4AF37]');
            }
        }
    </script>



</x-app-layout>
