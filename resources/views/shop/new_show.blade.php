<div class="bg-[#F8F8F9] min-h-screen font-sans antialiased text-gray-900">
        <div class="max-w-7xl5 mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">

            {{-- Breadcrumb --}}
            <nav class="mb-8 flex items-center gap-3 text-[13px] font-medium tracking-tight text-gray-400">
                <a href="{{ route('shop.index') }}"
                    class="hover:text-[#8f6a10] transition-colors flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Shop
                </a>
                <span class="text-gray-300">/</span>
                <span class="text-gray-600 truncate max-w-[200px]">{{ $product->name }}</span>
            </nav>

            {{-- Main Card --}}
            <div
                class="bg-white rounded-[2rem] border border-gray-100 shadow-[0_30px_60px_-15px_rgba(0,0,0,0.05)] overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-0">

                    {{-- Left: Image Gallery (Span 7) --}}
                    <div class="lg:col-span-7 p-4 sm:p-8 lg:p-10 bg-[#FCFCFD] border-r border-gray-50">
                        <div class="sticky top-10">
                            @php
                                $gallery = [];
                                if (isset($product->images) && count($product->images)) {
                                    foreach ($product->images as $img) {
                                        $gallery[] = asset('storage/' . $img->path);
                                    }
                                } elseif ($product->image ?? false) {
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
                                                    d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                            </svg>
                                        </button>
                                    </form>
                                @endauth

                                {{-- Main Image Display --}}
                                <div
                                    class="relative rounded-3xl overflow-hidden aspect-square bg-white shadow-inner mb-6">
                                    <div class="flex h-full transition-transform duration-700 cubic-bezier(0.4, 0, 0.2, 1)"
                                        data-gallery-track>
                                        @foreach ($gallery as $url)
                                            <div class="w-full h-full shrink-0">
                                                @if ($url)
                                                    <img src="{{ $url }}"
                                                        class="w-full h-full object-cover select-none"
                                                        alt="{{ $product->name }}">
                                                @else
                                                    <div
                                                        class="w-full h-full flex flex-col items-center justify-center text-gray-300 bg-gray-50">
                                                        <svg class="w-12 h-12 mb-2 opacity-20" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        <span class="text-xs uppercase tracking-widest">Image Coming
                                                            Soon</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Thumbnails --}}
                                @if (count($gallery) > 1)
                                    <div class="flex gap-4 justify-center" data-gallery-thumbs>
                                        @foreach ($gallery as $i => $url)
                                            <button type="button" data-thumb-index="{{ $i }}"
                                                class="group relative w-20 h-20 rounded-2xl overflow-hidden border-2 transition-all {{ $loop->first ? 'border-[#D4AF37]' : 'border-transparent' }}">
                                                <img src="{{ $url }}" class="w-full h-full object-cover">
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
                                In Stock
                            </div>

                            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight leading-tight mb-4">
                                {{ $product->name }}
                            </h1>

                            {{-- Price Display --}}
                            <div class="text-3xl font-light text-[#8f6a10] mb-8">
                                @if ($product->has_variants && $product->variants->count())
                                    @php
                                        $variantPrices = $product->variants->whereNotNull('price');
                                        $min = $variantPrices->min('price');
                                        $max = $variantPrices->max('price');
                                    @endphp
                                    <span class="font-semibold">RM {{ number_format($min, 2) }}</span>
                                    @if ($min != $max)
                                        <span class="text-gray-300 mx-1">–</span> RM {{ number_format($max, 2) }}
                                    @endif
                                @else
                                    <span class="font-semibold">RM {{ number_format($product->price ?? 0, 2) }}</span>
                                @endif
                            </div>

                            {{-- Feature Bar --}}
                            <div class="grid grid-cols-2 gap-4 mb-8">
                                <div
                                    class="flex items-center gap-3 p-3 rounded-2xl bg-gray-50/50 border border-gray-100">
                                    <div class="p-2 bg-white rounded-xl shadow-sm"><svg class="w-4 h-4 text-gray-600"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M5 13l4 4L19 7" />
                                        </svg></div>
                                    <span class="text-[12px] font-medium text-gray-600">Fast Shipping</span>
                                </div>
                                <div
                                    class="flex items-center gap-3 p-3 rounded-2xl bg-gray-50/50 border border-gray-100">
                                    <div class="p-2 bg-white rounded-xl shadow-sm"><svg class="w-4 h-4 text-gray-600"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3" />
                                        </svg></div>
                                    <span class="text-[12px] font-medium text-gray-600">7-Day Returns</span>
                                </div>
                            </div>

                            {{-- Description --}}
                            <div class="prose prose-sm text-gray-500 leading-relaxed mb-10">
                                {{ $product->short_description ?? 'A premium selection crafted for quality and durability.' }}
                            </div>

                            <hr class="border-gray-100 mb-8">

                            {{-- Form --}}
                            <form method="POST" action="{{ route('cart.add', $product) }}" class="space-y-8">
                                @csrf

                                {{-- Variants --}}
                                @if ($product->has_variants && $product->options->count())
                                    <div id="variant-picker" class="space-y-6">
                                        @foreach ($product->options as $option)
                                            <div>
                                                <label
                                                    class="block text-[11px] font-bold uppercase tracking-widest text-gray-400 mb-3">
                                                    Select {{ $option->label ?? $option->name }}
                                                </label>
                                                <div class="flex flex-wrap gap-2.5">
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
                                        <input type="hidden" name="variant_id" id="variant_id">
                                    </div>
                                @endif

                                {{-- Quantity & Action --}}
                                <div class="flex items-end gap-4">
                                    <div class="w-32">
                                        <label
                                            class="block text-[11px] font-bold uppercase tracking-widest text-gray-400 mb-3">Qty</label>
                                        <div
                                            class="flex items-center h-14 rounded-2xl border border-gray-200 bg-white overflow-hidden shadow-sm">
                                            <button type="button"
                                                class="flex-1 h-full text-gray-400 hover:text-gray-900 transition"
                                                onclick="stepDown(this)">–</button>
                                            <input type="number" name="quantity" value="1" min="1"
                                                class="w-10 text-center border-0 focus:ring-0 font-bold text-gray-900">
                                            <button type="button"
                                                class="flex-1 h-full text-gray-400 hover:text-gray-900 transition"
                                                onclick="stepUp(this)">+</button>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <button type="submit"
                                            class="w-full h-14 bg-[#1a1a1a] text-white rounded-2xl font-bold text-sm uppercase tracking-widest hover:bg-black transition-all shadow-xl shadow-black/10 flex items-center justify-center gap-3 group">
                                            <span>Add to Bag</span>
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
            <div class="mt-16 max-w-4xl mx-auto">
                <div class="flex justify-center gap-12 border-b border-gray-100 mb-10">
                    <button onclick="switchTab('desc')" id="tab-btn-desc"
                        class="pb-4 text-sm font-bold uppercase tracking-widest border-b-2 border-[#D4AF37] text-gray-900">Description</button>
                    <button onclick="switchTab('info')" id="tab-btn-info"
                        class="pb-4 text-sm font-bold uppercase tracking-widest border-b-2 border-transparent text-gray-400 hover:text-gray-900 transition">Specifications</button>
                </div>

                <div id="tab-desc" class="prose prose-base max-w-none text-gray-600 leading-relaxed">
                    {!! $product->description ?? 'Detailed description coming soon.' !!}
                </div>

                <div id="tab-info" class="hidden">
                    <div class="grid grid-cols-1 gap-1">
                        @forelse ($product->specs ?? [] as $row)
                            <div class="flex justify-between py-4 border-b border-gray-50 items-center">
                                <span
                                    class="text-sm font-bold text-gray-900 uppercase tracking-tighter">{{ $row['name'] }}</span>
                                <span class="text-sm text-gray-500">{{ $row['value'] }}</span>
                            </div>
                        @empty
                            <p class="text-center text-gray-400 py-10">No detailed specifications available.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            
        </div>
    </div>