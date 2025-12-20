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
                class="bg-white rounded-2xl border border-[#D4AF37]/18 shadow-[0_18px_40px_rgba(0,0,0,0.06)] p-4 sm:p-6 lg:p-7">

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
                            <div class="relative rounded-2xl overflow-hidden bg-gray-100 aspect-[4/3] mb-3">

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
                        {{-- 名称 --}}
                        <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900">
                            {{ $product->name }}
                        </h1>

                        {{-- 价格 --}}
                        <div class="mt-2 flex items-center gap-3">
                            <div class="text-2xl font-semibold text-[#8f6a10]" data-product-price>
                                RM {{ number_format($product->price, 2) }}
                            </div>
                            {{-- 可以以后加上划线原价 --}}
                            {{-- <div class="text-sm text-gray-400 line-through">RM 129.90</div> --}}
                        </div>

                        {{-- 小信息 --}}
                        <div class="mt-2 text-xs text-gray-500">
                            {{-- 以后可以放 SKU / Stock --}}
                            {{-- SKU: {{ $product->sku ?? '—' }} --}}
                            Ready stock · Ships in 1–3 working days
                        </div>

                        {{-- 分割线 --}}
                        <div class="mt-4 mb-4 border-t border-gray-100"></div>

                        {{-- 描述 --}}
                        <div class="text-sm text-gray-700 leading-relaxed space-y-2">
                            @if ($product->description)
                                <p>{{ $product->description }}</p>
                            @else
                                <p class="text-gray-500 text-sm">
                                    No description for this product yet.
                                </p>
                            @endif
                        </div>

                        {{-- Variant 选择区块 --}}
                        {{-- Variant 选择（如果有） --}}
                        @if ($product->has_variants && $product->options->count())
                            @php
                                // 准备 variant map 给 JS 用
                                $variantMap = $product->variants
                                    ->map(function ($variant) {
                                        return [
                                            'id' => $variant->id,
                                            'price' => $variant->price,
                                            'stock' => $variant->stock,
                                            'image' => $variant->image ? asset('storage/' . $variant->image) : null,
                                            'options' => $variant->options ?? [], // 例如 ["Color" => "Red", "Size" => "M"]
                                        ];
                                    })
                                    ->values();
                            @endphp

                            <div class="mt-5 space-y-4" id="product-variants"
                                data-variants='@json($variantMap)'>
                                @foreach ($product->options as $option)
                                    <div>
                                        <p class="text-[11px] uppercase tracking-wide text-gray-400 mb-1">
                                            {{ $option->label ?? $option->name }}
                                        </p>
                                        <div class="flex flex-wrap gap-2" data-option-group="{{ $option->name }}">
                                            @foreach ($option->values as $value)
                                                <button type="button"
                                                    class="variant-pill px-4 py-1.5 rounded-full border border-gray-300 text-sm text-gray-800
                                   hover:border-[#D4AF37] hover:text-[#8f6a10] transition"
                                                    data-option-name="{{ $option->name }}"
                                                    data-option-label="{{ $option->label ?? $option->name }}"
                                                    data-option-value="{{ $value->value }}">
                                                    {{ $value->value }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                {{-- 选中组合结果状态提示 --}}
                                <p class="text-xs text-gray-500" id="variant-status">
                                    Please select all options.
                                </p>

                                {{-- 隐藏字段，Add to Cart 用 --}}
                                <input type="hidden" name="variant_id" id="variant_id">
                            </div>
                        @endif

                        {{-- 再一条细分割线 --}}
                        <div class="mt-5 mb-4 border-t border-gray-100"></div>

                        {{-- Add to cart 区块 --}}
                        <form method="POST" action="{{ route('cart.add', $product) }}" class="mt-auto">
                            @csrf
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
                                                d="M2.25 3.75h2.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25h9.75l2.25-7.5H6.106M7.5 14.25L5.706 6.022M7.5 14.25l-1.5 4.5m0 0h12.75m-12.75 0a1.5 1.5 0 1 0 3 0m9.75 0a1.5 1.5 0 1 0 3 0" />
                                        </svg>
                                        Add to Cart
                                    </button>
                                </div>

                            </div>
                        </form>

                        {{-- 下面可以以后放：shipping info / returns policy --}}
                        <div class="mt-4 text-[11px] text-gray-500">
                            Free shipping over RM 150 · Easy returns within 7 days
                        </div>
                    </div>
                </div>
            </div>

            {{-- 以后可以在这里加 related products --}}
            {{-- @include('shop.partials.related', [...]) --}}

        </div>
    </div>
    {{-- <script>
        document.addEventListener("DOMContentLoaded", () => {

        });
    </script> --}}

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // ===== Variant logic =====
            const variantRoot = document.getElementById('product-variants');
            const addToCartBtn = document.querySelector('form[action*="cart.add"] button[type="submit"]');
            const variantIdInput = document.getElementById('variant_id');
            const variantStatus = document.getElementById('variant-status');
            const priceDisplay = document.querySelector('[data-product-price]'); // 我下面教你加这个 attribute

            if (variantRoot) {
                const variants = JSON.parse(variantRoot.dataset.variants || '[]');
                const pills = Array.from(variantRoot.querySelectorAll('.variant-pill'));
                const selections = {}; // { Color: "Red", Size: "M" }

                // 一开始禁止下单
                if (addToCartBtn) {
                    addToCartBtn.disabled = true;
                    addToCartBtn.classList.add('opacity-60', 'cursor-not-allowed');
                }

                const refreshSelectedStyle = () => {
                    pills.forEach(btn => {
                        const name = btn.dataset.optionName;
                        const value = btn.dataset.optionValue;
                        const selected = selections[name] === value;

                        if (selected) {
                            btn.classList.add('border-[#D4AF37]', 'text-[#8f6a10]', 'bg-[#F9F4E5]');
                            btn.classList.remove('border-gray-300', 'text-gray-800', 'bg-white');
                        } else {
                            btn.classList.remove('border-[#D4AF37]', 'text-[#8f6a10]', 'bg-[#F9F4E5]');
                            btn.classList.add('border-gray-300', 'text-gray-800');
                        }
                    });
                };

                const findVariant = () => {
                    if (!variants.length) return null;

                    // 检查是否所有 option 都已选择
                    const optionGroups = Array.from(variantRoot.querySelectorAll('[data-option-group]'))
                        .map(g => g.getAttribute('data-option-group'));
                    for (const name of optionGroups) {
                        if (!selections[name]) {
                            return null;
                        }
                    }

                    // 找到匹配的 variant
                    return variants.find(v => {
                        if (!v.options) return false;
                        for (const [key, val] of Object.entries(v.options)) {
                            if (selections[key] !== val) return false;
                        }
                        return true;
                    }) || null;
                };

                const updateVariantState = () => {
                    refreshSelectedStyle();
                    const variant = findVariant();

                    if (!variant) {
                        variantIdInput.value = '';
                        if (variantStatus) {
                            variantStatus.textContent = 'This combination is not available.';
                            variantStatus.classList.remove('text-gray-500');
                            variantStatus.classList.add('text-red-500');
                        }
                        if (addToCartBtn) {
                            addToCartBtn.disabled = true;
                            addToCartBtn.classList.add('opacity-60', 'cursor-not-allowed');
                        }
                        return;
                    }

                    variantIdInput.value = variant.id;

                    if (variantStatus) {
                        variantStatus.textContent = variant.stock > 0 ?
                            `Selected: In stock (${variant.stock})` :
                            'Selected combination is out of stock.';
                        variantStatus.classList.remove('text-red-500');
                        variantStatus.classList.add('text-gray-500');
                    }

                    // 更新价格显示（如果 variant 有自己的 price）
                    if (priceDisplay && variant.price) {
                        priceDisplay.textContent = `RM ${Number(variant.price).toFixed(2)}`;
                    }

                    if (addToCartBtn) {
                        const disabled = variant.stock <= 0;
                        addToCartBtn.disabled = disabled;
                        addToCartBtn.classList.toggle('opacity-60', disabled);
                        addToCartBtn.classList.toggle('cursor-not-allowed', disabled);
                    }
                };

                pills.forEach(btn => {
                    btn.addEventListener('click', () => {
                        const name = btn.dataset.optionName;
                        const value = btn.dataset.optionValue;

                        if (selections[name] === value) {
                            // 再点一次取消选择（可选）
                            delete selections[name];
                        } else {
                            selections[name] = value;
                        }

                        updateVariantState();
                    });
                });
            }

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



</x-app-layout>
