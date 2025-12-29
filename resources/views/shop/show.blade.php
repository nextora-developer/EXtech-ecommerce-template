<x-app-layout>
    <div class="bg-[#F5F5F7] min-h-screen">
        <div class="max-w-7xl5 mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

            {{-- Back / breadcrumb --}}
            <div class="mb-4 flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('shop.index') }}" class="hover:text-[#8f6a10]">
                    ← Back to Shop
                </a>
                <span class="text-gray-400">/</span>
                <span class="truncate">
                    {{ $product->name }}
                </span>
            </div>

            {{-- Main card --}}
            <div
                class="relative bg-white rounded-2xl border border-[#D4AF37]/18 shadow-[0_18px_40px_rgba(0,0,0,0.06)] p-4 sm:p-6 lg:p-7">


                {{-- ❤️ Favorite button --}}
                @auth
                    @php
                        $isFavorited = auth()->user()->favorites->contains('product_id', $product->id);
                    @endphp

                    <form
                        action="{{ $isFavorited ? route('account.favorites.destroy', $product) : route('account.favorites.store', $product) }}"
                        method="POST" class="absolute top-3 right-3 z-30">
                        @csrf
                        @if ($isFavorited)
                            @method('DELETE')
                        @endif

                        <button type="submit"
                            class="w-9 h-9 flex items-center justify-center rounded-full bg-white/90 backdrop-blur hover:bg-white shadow-sm border border-gray-200 transition">

                            @if ($isFavorited)
                                {{-- 填充 ♥ --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="#D4AF37" viewBox="0 0 24 24" class="h-5 w-5">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36
                                                                         2 12.28 2 8.5 2 5.42 4.42
                                                                         3 7.5 3c1.74 0 3.41.81 4.5
                                                                         2.09C13.09 3.81 14.76 3 16.5
                                                                         3 19.58 3 22 5.42 22 8.5c0
                                                                         3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                </svg>
                            @else
                                {{-- 空心 ♥ --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#8f6a10" stroke-width="1.8"
                                    viewBox="0 0 24 24" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.5c0-2.8-2.2-5-5-5-1.9
                                                                       0-3.6 1-4.5 2.5C10.6 4.5
                                                                       8.9 3.5 7 3.5 4.2 3.5 2
                                                                       5.7 2 8.5c0 5.2 5.5 8.9
                                                                       9.8 12.7.1.1.3.1.4
                                                                       0C15.5 17.4 21 13.7 21 8.5z" />
                                </svg>
                            @endif
                        </button>
                    </form>
                @endauth


                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-10">

                    {{-- 左边：图片 --}}
                    <div>
                        @php
                            // gallery array
                            $gallery = [];

                            if (isset($product->images) && count($product->images)) {
                                foreach ($product->images as $img) {
                                    $gallery[] = asset('storage/' . $img->path);
                                }
                            }

                            if ($product->image ?? false) {
                                $gallery[] = asset('storage/' . $product->image);
                            }

                            if (!count($gallery)) {
                                $gallery[] = null;
                            }
                        @endphp

                        <div data-gallery class="max-w-lg mx-auto">

                            {{-- 大图：跟首页更新一致 aspect-[4/3] --}}
                            <div class="relative rounded-2xl overflow-hidden bg-black aspect-square mb-3">

                                <div class="flex h-full transition-transform duration-500 ease-out" data-gallery-track>
                                    @foreach ($gallery as $url)
                                        <div class="w-full h-full shrink-0">
                                            @if ($url)
                                                <img src="{{ $url }}" class="w-full h-full object-cover"
                                                    alt="{{ $product->name }}">
                                            @else
                                                <div
                                                    class="w-full h-full flex items-center justify-center text-gray-400 text-xs">
                                                    Image coming soon
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                {{-- 遮罩 --}}
                                <div
                                    class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/25 via-black/0 to-transparent">
                                </div>

                                {{-- 左右按钮 --}}
                                @if (count($gallery) > 1)
                                    <button type="button"
                                        class="hidden sm:flex absolute left-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-black/45 hover:bg-black/70 text-white items-center justify-center text-xs"
                                        data-gallery-prev>‹</button>

                                    <button type="button"
                                        class="hidden sm:flex absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-black/45 hover:bg-black/70 text-white items-center justify-center text-xs"
                                        data-gallery-next>›</button>
                                @endif

                            </div>

                            {{-- 小缩略图 --}}
                            @if (count($gallery) > 1)
                                <div class="flex gap-2 overflow-x-auto scrollbar-hide" data-gallery-thumbs>
                                    @foreach ($gallery as $i => $url)
                                        <button type="button" data-thumb-index="{{ $i }}"
                                            class="relative w-16 h-16 rounded-lg overflow-hidden border border-gray-200 flex-shrink-0 thumb-btn">
                                            @if ($url)
                                                <img src="{{ $url }}" class="w-full h-full object-cover">
                                            @else
                                                <div
                                                    class="w-full h-full flex items-center justify-center text-gray-400 text-xs">
                                                    -</div>
                                            @endif
                                            <span
                                                class="absolute inset-0 bg-black/30 opacity-0 thumb-overlay transition"></span>
                                        </button>
                                    @endforeach
                                </div>
                            @endif

                        </div>
                    </div>

                    {{-- 右边：详情 --}}
                    <div class="flex flex-col h-full">

                        {{-- 标题 + 价格 --}}
                        <div>
                            {{-- 名称 --}}
                            <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900">
                                {{ $product->name }}
                            </h1>

                            {{-- 价格 --}}
                            <div class="mt-5 flex items-end gap-3">
                                <div class="text-2xl font-semibold text-[#8f6a10]" data-product-price>
                                    @if ($product->has_variants && $product->variants->count())
                                        @php
                                            $variantPrices = $product->variants->whereNotNull('price');
                                            $min = $variantPrices->min('price');
                                            $max = $variantPrices->max('price');
                                        @endphp

                                        @if ($min === null)
                                            RM 0.00
                                        @elseif ($min == $max)
                                            RM {{ number_format($min, 2) }}
                                        @else
                                            RM {{ number_format($min, 2) }} – {{ number_format($max, 2) }}
                                        @endif
                                    @else
                                        RM {{ number_format($product->price ?? 0, 2) }}
                                    @endif
                                </div>

                                {{-- 小状态 badge --}}
                                <div
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full
                        bg-emerald-50 text-emerald-700 text-sm font-medium border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Ready stock
                                </div>
                            </div>

                            {{-- 小信息条：发货 / 运费提示 --}}
                            <div class="mt-5 inline-flex flex-wrap gap-2 text-sm text-gray-500">
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-gray-100">
                                    Ships in 1–3 working days
                                </span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-gray-100">
                                    Free shipping over RM 150
                                </span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-gray-100">
                                    Easy returns within 7 days
                                </span>
                            </div>
                        </div>

                        {{-- 分割线 --}}
                        <div class="mt-5 mb-4 border-t border-gray-100"></div>

                        {{-- 描述 --}}
                        <div class="text-sm text-gray-700 leading-relaxed space-y-2 break-words max-w-xl">
                            @if ($product->short_description)
                                <p>{{ $product->short_description }}</p>
                            @else
                                <p class="text-gray-500 text-sm">
                                    No description for this product yet.
                                </p>
                            @endif
                        </div>

                        {{-- 再一条细分割线，把说明和变体区隔开 --}}
                        <div class="mt-5 border-t border-gray-100"></div>

                        {{-- Add to cart + Variant 表单 --}}
                        <form method="POST" action="{{ route('cart.add', $product) }}"
                            class="mt-5 flex flex-col gap-5">
                            @csrf

                            {{-- Variant 选择（分组：Color / Size 这种） --}}
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

                                <div id="variant-picker" data-variants='@json($variantMap)' class="space-y-4">
                                    @foreach ($product->options as $option)
                                        <div>
                                            <p class="text-[11px] uppercase tracking-[0.16em] text-gray-500 mb-1">
                                                {{ $option->label ?? $option->name }}
                                            </p>

                                            <div class="flex flex-wrap gap-2" data-option-key="{{ $option->name }}">
                                                @foreach ($option->values as $value)
                                                    <button type="button"
                                                        class="variant-pill px-3.5 py-1.5 rounded-full border border-gray-300
                                           text-xs sm:text-sm text-gray-800 bg-white
                                           hover:border-[#D4AF37] hover:text-[#8f6a10] hover:bg-[#F9F4E5] transition"
                                                        data-option-key="{{ $option->name }}"
                                                        data-option-value="{{ $value->value }}">
                                                        {{ $value->value }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach

                                    <p class="text-xs text-[#B28A15]" id="variant-status">
                                        请先选择所有选项组合。
                                    </p>

                                    {{-- 这个 hidden 一定在 form 里面 --}}
                                    <input type="hidden" name="variant_id" id="variant_id">
                                </div>
                            @endif

                            {{-- 数量 + 加入购物车 --}}
                            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                {{-- 数量 --}}
                                <div>
                                    <label class="block text-[11px] uppercase tracking-wide text-gray-400 mb-1">
                                        Quantity
                                    </label>
                                    <div
                                        class="inline-flex items-center rounded-xl border border-gray-200 bg-white px-2">
                                        <button type="button" class="px-2 text-gray-500 text-sm"
                                            onclick="const input = this.parentElement.querySelector('input'); if (parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;">
                                            -
                                        </button>
                                        <input type="number" name="quantity" value="1" min="1"
                                            class="w-12 text-center border-0 focus:ring-0 text-sm text-gray-800">
                                        <button type="button" class="px-2 text-gray-500 text-sm"
                                            onclick="const input = this.parentElement.querySelector('input'); input.value = parseInt(input.value || 1) + 1;">
                                            +
                                        </button>
                                    </div>
                                </div>

                                {{-- Add to cart 按钮 --}}
                                <div class="flex-1">
                                    <label class="block text-[11px] uppercase tracking-wide text-transparent mb-1">
                                        &nbsp;
                                    </label>
                                    <button type="submit"
                                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full
                           bg-[#D4AF37] text-white text-sm font-semibold
                           shadow-[0_10px_25px_rgba(0,0,0,0.18)]
                           hover:bg-[#b8942f] transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.25 3.75h2.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25h9.75l2.25-7.5H6.106M7.5 14.25L5.706 6.022M7.5 14.25l-1.5 4.5m0 0h12.75m-12.75 0a1.5 1.5 0 1 0 3 0m-9.75 0a1.5 1.5 0 1 0 3 0" />
                                        </svg>
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>

                </div>
            </div>

            {{-- Tabs Section --}}
            <div
                class="mt-8 bg-white rounded-2xl border border-[#D4AF37]/18 shadow-[0_18px_40px_rgba(0,0,0,0.06)] p-6">

                {{-- Tab Headers --}}
                <div class="flex border-b border-gray-200 mb-4">
                    <button type="button" id="tab-btn-desc" onclick="switchTab('desc')"
                        class="px-4 py-2 text-sm font-semibold border-b-2
               text-gray-700 border-[#D4AF37]">
                        Long Description
                    </button>

                    <button type="button" id="tab-btn-info" onclick="switchTab('info')"
                        class="px-4 py-2 text-sm font-semibold border-b-2
               text-gray-500 border-transparent hover:text-[#8f6a10]">
                        Additional Info
                    </button>
                </div>


                {{-- Content: Description --}}
                <div id="tab-desc" class="text-sm text-gray-700 leading-relaxed space-y-2 break-words">
                    @if ($product->description)
                        {!! $product->description !!}
                    @else
                        <p class="text-gray-500 text-sm">No description for this product yet.</p>
                    @endif
                </div>

                {{-- Content: Additional Info --}}
                <div id="tab-info" class="hidden text-sm leading-relaxed">

                    @if (!empty($product->specs))
                        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">

                            <div class="px-4 py-3 border-b bg-gray-50 rounded-t-2xl">
                                <h4 class="font-semibold text-gray-700">Product Specifications</h4>
                            </div>

                            <dl class="divide-y">
                                @foreach ($product->specs as $row)
                                    <div
                                        class="grid grid-cols-[160px,1fr] gap-6 px-4 py-2 hover:bg-gray-50 transition">
                                        <dt class="font-medium text-gray-500">
                                            {{ $row['name'] ?? '-' }}
                                        </dt>
                                        <dd class="text-gray-800">
                                            {{ $row['value'] ?? '-' }}
                                        </dd>
                                    </div>
                                @endforeach
                            </dl>

                        </div>
                    @else
                        <p class="text-gray-500 text-sm">No additional info yet.</p>
                    @endif

                </div>


            </div>


            {{-- 以后可以在这里加 related products --}}
            {{-- Related Products --}}
            @if ($related->count())
                <div class="mt-10">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        Related Products
                    </h2>

                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 sm:gap-6">
                        @foreach ($related as $item)
                            <a href="{{ route('shop.show', $item->slug) }}"
                                class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-[#D4AF37]/60 transition overflow-hidden flex flex-col">
                                {{-- Product image --}}
                                <div class="relative aspect-[4/3] bg-gray-100 overflow-hidden">
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
                                        @php
                                            $isFavorited = auth()
                                                ->user()
                                                ->favorites->contains('product_id', $product->id);
                                        @endphp

                                        <form
                                            action="{{ $isFavorited ? route('account.favorites.destroy', $product) : route('account.favorites.store', $product) }}"
                                            method="POST" class="absolute top-2 right-2 z-20">
                                            @csrf
                                            @if ($isFavorited)
                                                @method('DELETE')
                                            @endif

                                            <button type="submit"
                                                class="w-8 h-8 flex items-center justify-center rounded-full bg-white/80 backdrop-blur
                   hover:bg-white shadow-sm">

                                                @if ($isFavorited)
                                                    {{-- 已收藏：金色 ♥ --}}
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="#D4AF37"
                                                        viewBox="0 0 24 24" class="h-5 w-5">
                                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36
                                                                             2 12.28 2 8.5 2 5.42 4.42
                                                                             3 7.5 3c1.74 0 3.41.81 4.5
                                                                             2.09C13.09 3.81 14.76 3 16.5
                                                                             3 19.58 3 22 5.42 22 8.5c0
                                                                             3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                                    </svg>
                                                @else
                                                    {{-- 未收藏：空心 ♥ --}}
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        stroke="#8f6a10" stroke-width="1.8" viewBox="0 0 24 24"
                                                        class="h-5 w-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.5c0-2.8-2.2-5-5-5-1.9
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

                                    <div class="mt-2 flex items-center justify-between">
                                        <p class="text-sm font-semibold text-[#8f6a10]">
                                            RM {{ number_format($item->price, 2) }}
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
                </div>
            @endif
        </div>
    </div>

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
                            statusEl.textContent = '请先选择所有选项组合。';
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
                    statusEl.textContent = '已选择：' + parts.join(' • ');
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

            // 这里保留你之前的 gallery / 数量 JS...
            const gallery = document.querySelector("[data-gallery]");
            if (!gallery) return;

            const track = gallery.querySelector("[data-gallery-track]");
            const slides = Array.from(track.children);
            const prev = gallery.querySelector("[data-gallery-prev]");
            const next = gallery.querySelector("[data-gallery-next]");
            const thumbs = Array.from(gallery.querySelectorAll("[data-thumb-index]"));

            let index = 0;

            const go = (i) => {
                index = (i + slides.length) % slides.length;
                track.style.transform = `translateX(-${index * 100}%)`;

                thumbs.forEach((t, idx) => {
                    t.classList.toggle("border-[#D4AF37]", idx === index);
                    t.classList.toggle("border-gray-200", idx !== index);
                });
            };

            go(0);

            prev?.addEventListener("click", () => go(index - 1));
            next?.addEventListener("click", () => go(index + 1));

            thumbs.forEach((t) => {
                t.addEventListener("click", () => go(parseInt(t.dataset.thumbIndex) || 0));
            });

            let sx = 0;
            track.addEventListener("touchstart", (e) => sx = e.touches[0].clientX);
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
