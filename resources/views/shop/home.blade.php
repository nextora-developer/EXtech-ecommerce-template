<x-app-layout>
    <div class="bg-white">
        {{-- Banner：可滑动轮播，图片来自数据库 --}}
        <section class="w-full h-[260px] sm:h-[360px] lg:h-[420px] relative z-0 bg-white" data-banner-slider>
            <div class="max-w-7xl5 mx-auto h-full px-4 sm:px-6 lg:px-8 pt-5">
                <div class="relative h-full rounded-3xl overflow-hidden shadow-[0_18px_40px_rgba(0,0,0,0.25)]">

                    @if (isset($banners) && $banners->count())
                        {{-- 轨道 --}}
                        <div class="flex h-full transition-transform duration-700 ease-out" data-banner-track>
                            @foreach ($banners as $banner)
                                @php
                                    $url = $banner->link_url ?: route('shop.index');
                                @endphp

                                <a href="{{ $url }}" class="relative w-full h-full shrink-0 block group">
                                    <div class="w-full h-full bg-cover bg-center bg-no-repeat"
                                        style="background-image: url('{{ asset('storage/' . $banner->image_path) }}');">
                                    </div>

                                    {{-- 遮罩 --}}
                                    <div class="absolute inset-0 bg-black/20 group-hover:bg-black/30 transition"></div>

                                    {{-- @if ($banner->title)
                                <div class="absolute left-6 bottom-6 text-white">
                                    <p class="text-xs uppercase tracking-[0.15em] text-white/70 mb-1">BRIF Shop
                                    </p>
                                    <h2 class="text-lg sm:text-2xl font-semibold drop-shadow">
                                        {{ $banner->title }}
                                    </h2>
                                </div>
                            @endif --}}
                                </a>
                            @endforeach
                        </div>

                        {{-- 左右箭头 --}}
                        @if ($banners->count() > 1)
                            <button type="button"
                                class="hidden sm:flex absolute left-4 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-black/45 hover:bg-black/70 text-white items-center justify-center text-sm"
                                data-banner-prev>
                                ‹
                            </button>

                            <button type="button"
                                class="hidden sm:flex absolute right-4 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-black/45 hover:bg-black/70 text-white items-center justify-center text-sm"
                                data-banner-next>
                                ›
                            </button>

                            {{-- 小点点 --}}
                            <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2" data-banner-dots>
                                @foreach ($banners as $index => $banner)
                                    <button type="button"
                                        class="w-2.5 h-2.5 rounded-full bg-white/40 hover:bg-white/80 transition"
                                        data-banner-dot="{{ $index }}"></button>
                                @endforeach
                            </div>
                        @endif
                    @else
                        {{-- 没有 banner 的时候显示一个占位背景（你要可以再改） --}}
                        <div class="w-full h-full bg-[#F5F5F7] flex items-center justify-center">
                            <p class="text-gray-400 text-sm">BRIF Shop Banner coming soon</p>
                        </div>
                    @endif

                </div>
            </div>
        </section>




        {{-- Category 区块 --}}
        <section id="categories" class="bg-white">
            <div class="max-w-7xl5 mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10">
                {{-- <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-semibold text-gray-900">
                            Shop by category
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Browse BRIF Shop by product category.
                        </p>
                    </div>
                </div> --}}

                @if (isset($categories) && $categories->count())
                    {{-- 横向滑动 + 隐藏 scrollbar + 鼠标拖动 --}}
                    <div class="overflow-x-auto scrollbar-hide cursor-grab select-none" data-scroll-x>
                        <div class="flex gap-4 md:gap-5 min-w-max py-1">
                            @foreach ($categories as $category)
                                <a href="{{ route('shop.index', ['category' => $category->slug]) }}"
                                    class="group shrink-0 w-[110px] md:w-[120px] bg-white border border-gray-100 rounded-xl 
                                  px-3 py-4 shadow-sm hover:shadow-md hover:border-[#D4AF37]/70 transition flex flex-col items-center">

                                    {{-- icon --}}
                                    <div
                                        class="w-14 h-14 rounded-full bg-gray-50 flex items-center justify-center overflow-hidden mb-2">
                                        @if ($category->icon)
                                            <img src="{{ asset('storage/' . $category->icon) }}"
                                                alt="{{ $category->name }}"
                                                class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-200">
                                        @else
                                            <span class="text-[10px] text-gray-400 text-center">
                                                No image
                                            </span>
                                        @endif
                                    </div>

                                    {{-- name --}}
                                    <div
                                        class="text-[11px] sm:text-xs font-medium text-gray-800 text-center leading-snug line-clamp-2">
                                        {{ $category->name }}
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div
                        class="flex flex-col items-center justify-center border border-dashed border-gray-300 rounded-2xl bg-gray-50 py-10">
                        <p class="text-sm text-gray-500">
                            No categories yet. Add categories in admin to show them here.
                        </p>
                    </div>
                @endif

            </div>
        </section>


        {{-- Featured products --}}
        <section id="featured" class="bg-gray-50">
            <div class="max-w-7xl5 mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-semibold text-gray-900">
                            Featured products
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            A quick look at selected items from BRIF Shop.
                        </p>
                    </div>

                    <a href="{{ route('shop.index') }}"
                        class="hidden sm:inline-flex items-center text-sm font-medium text-[#8f6a10] hover:text-[#D4AF37]">
                        Browse all products
                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-1.5 h-4 w-4" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>

                @if ($featured->count())
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 sm:gap-6">
                        @foreach ($featured as $product)
                            <a href="{{ route('shop.show', $product->slug) }}"
                                class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-[#D4AF37]/60 transition overflow-hidden flex flex-col">
                                {{-- Product image --}}
                                <div class="relative aspect-[4/3] bg-gray-100 overflow-hidden">
                                    @if ($product->image ?? false)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div
                                            class="w-full h-full flex items-center justify-center text-xs text-gray-400">
                                            Image coming soon
                                        </div>
                                    @endif

                                    {{-- ❤️ Favorite button --}}
                                    @auth
                                        @php
                                            $isFavorited = auth()
                                                ->user()
                                                ->favorites->contains('product_id', $product->id);
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
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="#D4AF37"
                                                        viewBox="0 0 24 24" class="h-5 w-5">
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
                                                    // 拿有填 price 的 variants
                                                    $variantPrices = $product->variants->whereNotNull('price');
                                                    $min = $variantPrices->min('price');
                                                    $max = $variantPrices->max('price');
                                                @endphp

                                                @if ($min === null)
                                                    {{-- 有 variants 但是都没有填价钱 --}}
                                                    RM 0.00
                                                @elseif ($min == $max)
                                                    {{-- 所有 variants 同一个价钱 --}}
                                                    RM {{ number_format($min, 2) }}
                                                @else
                                                    {{-- 显示价钱范围 --}}
                                                    RM {{ number_format($min, 2) }} – {{ number_format($max, 2) }}
                                                @endif
                                            @else
                                                {{-- 没有 variants，用 product 本身的 price --}}
                                                RM {{ number_format($product->price ?? 0, 2) }}
                                            @endif
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
                @else
                    <div
                        class="flex flex-col items-center justify-center border border-dashed border-gray-300 rounded-2xl bg-white py-10">
                        <p class="text-sm text-gray-500">
                            No products yet. Add products in your admin panel to show them here.
                        </p>
                        <a href="{{ route('shop.index') }}"
                            class="mt-3 inline-flex items-center px-4 py-2 rounded-full text-xs font-medium bg-gray-900 text-white hover:bg-black">
                            Go to shop page
                        </a>
                    </div>
                @endif
            </div>
        </section>

        {{-- Bottom CTA --}}
        <section class="bg-white border-t border-gray-200">
            <div
                class="max-w-7xl5 mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold text-gray-900">
                        Ready to explore more from BRIF Shop?
                    </p>
                    <p class="text-xs sm:text-sm text-gray-500">
                        Browse categories, check details and complete your order in just a few steps.
                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('shop.index') }}"
                        class="inline-flex items-center px-4 py-2 rounded-full text-xs sm:text-sm font-medium bg-[#D4AF37] text-black hover:bg-[#f5d68a]">
                        Start shopping now
                    </a>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slider = document.querySelector('[data-scroll-x]');
            if (!slider) return;

            let isDown = false;
            let startX = 0;
            let moved = false;

            // 鼠标按下
            slider.addEventListener('mousedown', function(e) {
                isDown = true;
                moved = false;
                slider.classList.add('cursor-grabbing');

                e.preventDefault();
                startX = e.clientX;
            });

            // 鼠标抬起 / 离开
            const stopDrag = () => {
                isDown = false;
                slider.classList.remove('cursor-grabbing');
            };

            slider.addEventListener('mouseup', stopDrag);
            slider.addEventListener('mouseleave', stopDrag);

            // 鼠标移动：增量拖动（每次用上一次的位置当参考，会比较顺）
            slider.addEventListener('mousemove', function(e) {
                if (!isDown) return;

                e.preventDefault();
                const x = e.clientX;
                const delta = x - startX;

                // 灵敏度：1.2 可以自己调（1.0 更稳，1.5 更敏感）
                slider.scrollLeft -= delta * 1.2;

                startX = x; // 更新起点，下一次从这里算
                if (Math.abs(delta) > 3) moved = true;
            });

            // 拖动时不要触发里面 a 的点击
            slider.addEventListener('click', function(e) {
                if (moved) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            }, true);

            // 滚轮 -> 横向滚动，稍微顺一点
            // slider.addEventListener('wheel', function(e) {
            //     if (Math.abs(e.deltaY) > Math.abs(e.deltaX)) {
            //         e.preventDefault();
            //         slider.scrollLeft += e.deltaY * 0.7;
            //     }
            // }, {
            //     passive: false
            // });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slider = document.querySelector('[data-banner-slider]');
            if (!slider) return;

            const track = slider.querySelector('[data-banner-track]');
            const slides = Array.from(track.children);
            const prevBtn = slider.querySelector('[data-banner-prev]');
            const nextBtn = slider.querySelector('[data-banner-next]');
            const dotsWrap = slider.querySelector('[data-banner-dots]');
            const dots = dotsWrap ? Array.from(dotsWrap.querySelectorAll('[data-banner-dot]')) : [];

            let index = 0;
            let autoTimer = null;

            function goTo(i) {
                if (!slides.length) return;
                index = (i + slides.length) % slides.length;
                track.style.transform = `translateX(-${index * 100}%)`;

                // 更新底部点
                dots.forEach((dot, idx) => {
                    if (idx === index) {
                        dot.classList.add('bg-white');
                        dot.classList.remove('bg-white/40');
                    } else {
                        dot.classList.remove('bg-white');
                        dot.classList.add('bg-white/40');
                    }
                });
            }

            function next() {
                goTo(index + 1);
            }

            function prev() {
                goTo(index - 1);
            }

            // 初始
            goTo(0);

            // 按钮
            if (prevBtn) prevBtn.addEventListener('click', () => {
                prev();
                restartAuto();
            });

            if (nextBtn) nextBtn.addEventListener('click', () => {
                next();
                restartAuto();
            });

            // 点点点击
            dots.forEach((dot, idx) => {
                dot.addEventListener('click', () => {
                    goTo(idx);
                    restartAuto();
                });
            });

            // Auto slide
            function startAuto() {
                if (autoTimer) clearInterval(autoTimer);
                autoTimer = setInterval(() => {
                    next();
                }, 5000); // 5 秒一张
            }

            function restartAuto() {
                startAuto();
            }

            startAuto();

            // Touch swipe 支持（手机左右划）
            let startX = null;
            let isTouchMoving = false;

            slider.addEventListener('touchstart', (e) => {
                if (!e.touches[0]) return;
                startX = e.touches[0].clientX;
                isTouchMoving = true;
            });

            slider.addEventListener('touchmove', (e) => {
                if (!isTouchMoving || startX === null) return;
                const currentX = e.touches[0].clientX;
                const diff = currentX - startX;

                // 不做实时拖动，只是记录 swipe 方向
                // 如要实时拖动可以改这里
            });

            slider.addEventListener('touchend', (e) => {
                if (!isTouchMoving || startX === null) return;
                const endX = e.changedTouches[0].clientX;
                const diff = endX - startX;

                if (Math.abs(diff) > 50) {
                    if (diff < 0) {
                        next();
                    } else {
                        prev();
                    }
                    restartAuto();
                }

                startX = null;
                isTouchMoving = false;
            });
        });
    </script>



</x-app-layout>
