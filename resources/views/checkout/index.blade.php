<x-app-layout>
    <div class="bg-[#f7f7f9] py-10">
        <div class="max-w-7xl5 mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <nav class="text-sm text-gray-500 mb-4">
                <a href="{{ route('home') }}" class="hover:text-[#8f6a10]">Home</a>
                <span class="mx-1">/</span>
                <a href="{{ route('cart.index') }}" class="hover:text-[#8f6a10]">Cart</a>
                <span class="mx-1">/</span>
                <span class="text-gray-400">Checkout</span>
            </nav>

            {{-- 整个 checkout 表单 --}}
            <form method="POST" action="{{ route('checkout.store') }}" enctype="multipart/form-data">
                @csrf

                <section class="bg-transparent p-0 flex flex-col gap-6 lg:grid lg:grid-cols-5 lg:gap-8">

                    <div class="lg:col-span-3 space-y-4">

                        {{-- 左：信息 card --}}
                        <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8 lg:col-span-2">

                            <div class="mb-4">
                                <h1 class="text-lg font-semibold text-[#0A0A0C] mb-1">
                                    Checkout
                                </h1>
                                <p class="text-sm text-gray-500">
                                    Please fill in your details to complete the order
                                </p>
                            </div>

                            {{-- 🔹 Saved Addresses：地址切换 --}}
                            @if (isset($addresses) && $addresses->count())
                                <div class="mb-6">
                                    {{-- <p class="text-xs font-medium text-gray-500 mb-2 uppercase tracking-[0.16em]">
                                    Saved Addresses
                                </p> --}}

                                    <div class="flex gap-3 overflow-x-auto pb-1 no-scrollbar" data-address-scroller>
                                        @foreach ($addresses as $addr)
                                            @php
                                                $isDefault =
                                                    isset($defaultAddress) && $defaultAddress->id === $addr->id;
                                                $fullAddress = trim(
                                                    implode(
                                                        ', ',
                                                        array_filter([
                                                            $addr->address_line1 ?? null,
                                                            $addr->address_line2 ?? null,
                                                            ($addr->postcode ?? null) . ' ' . ($addr->city ?? null),
                                                            $addr->state ?? null,
                                                            $addr->country ?? null,
                                                        ]),
                                                    ),
                                                );
                                            @endphp

                                            <button type="button" data-address-choice
                                                data-name="{{ $addr->recipient_name ?? '' }}"
                                                data-phone="{{ $addr->phone ?? '' }}"
                                                data-email="{{ $addr->email ?? '' }}"
                                                data-address_line1="{{ $addr->address_line1 ?? '' }}"
                                                data-address_line2="{{ $addr->address_line2 ?? '' }}"
                                                data-postcode="{{ $addr->postcode ?? '' }}"
                                                data-city="{{ $addr->city ?? '' }}"
                                                data-state="{{ $addr->state ?? '' }}"
                                                data-country="{{ $addr->country ?? '' }}"
                                                class="min-w-[230px] text-left rounded-2xl border px-4 py-3 text-xs
                            {{ $isDefault ? 'border-[#D4AF37] bg-[#FDF7E7]' : 'border-gray-200 bg-gray-50' }}
                            hover:border-[#D4AF37] hover:bg-[#FDF3D7] transition">

                                                <div class="flex items-center justify-between mb-1">
                                                    <span class="font-semibold text-gray-900 truncate">
                                                        {{ $addr->recipient_name ?? 'Recipient' }}
                                                    </span>
                                                    @if ($isDefault)
                                                        <span
                                                            class="ml-2 px-2 py-0.5 rounded-full bg-[#D4AF37] text-[10px] font-semibold text-white">
                                                            Default
                                                        </span>
                                                    @endif
                                                </div>

                                                <p class="text-gray-500 line-clamp-2">
                                                    {{ $fullAddress }}
                                                </p>

                                                @if (!empty($addr->phone))
                                                    <p class="text-gray-400 mt-1">
                                                        📞 {{ $addr->phone }}
                                                    </p>
                                                @endif
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- 验证错误 --}}
                            @if ($errors->any())
                                <div class="mb-4 border border-red-200 bg-red-50 text-red-700 text-sm rounded-xl p-3">
                                    <ul class="list-disc ml-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="space-y-6">

                                {{-- 联系人 + 电话 + Email --}}
                                <div class="grid sm:grid-cols-3 gap-4">

                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-2">
                                            Full Name
                                        </label>
                                        <input type="text" name="name"
                                            value="{{ old('name', $defaultAddress->recipient_name ?? (auth()->user()->name ?? '')) }}"
                                            class="w-full px-3 py-3 rounded-xl border-gray-300 focus:border-[#D4AF37] focus:ring-[#D4AF37] text-sm"
                                            placeholder="e.g. John Tan" required>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-2">
                                            Phone Number
                                        </label>
                                        <input type="text" name="phone"
                                            value="{{ old('phone', $defaultAddress->phone ?? '') }}"
                                            class="w-full px-3 py-3 rounded-xl border-gray-300 focus:border-[#D4AF37] focus:ring-[#D4AF37] text-sm"
                                            placeholder="e.g. 012-3456789" required>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-2">
                                            Email Address
                                        </label>
                                        <input type="email" name="email"
                                            value="{{ old('email', $defaultAddress->email ?? '') }}"
                                            class="w-full px-3 py-3 rounded-xl border-gray-300 focus:border-[#D4AF37] focus:ring-[#D4AF37] text-sm"
                                            placeholder="name@example.com" required>
                                    </div>
                                </div>


                                {{-- Row 1 --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-2">
                                            Address Line 1
                                        </label>
                                        <input type="text" name="address_line1"
                                            value="{{ old('address_line1', $defaultAddress->address_line1 ?? '') }}"
                                            class="w-full px-3 py-3 rounded-xl border-gray-300 focus:border-[#D4AF37] focus:ring-[#D4AF37] text-sm"
                                            placeholder="Street, building, unit number" required>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-2">
                                            Address Line 2 (optional)
                                        </label>
                                        <input type="text" name="address_line2"
                                            value="{{ old('address_line2', $defaultAddress->address_line2 ?? '') }}"
                                            class="w-full px-3 py-3 rounded-xl border-gray-300 focus:border-[#D4AF37] focus:ring-[#D4AF37] text-sm"
                                            placeholder="Apartment / Floor / Block">
                                    </div>
                                </div>


                                {{-- Row 2 --}}
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-2">
                                            Postcode
                                        </label>
                                        <input type="text" name="postcode"
                                            value="{{ old('postcode', $defaultAddress->postcode ?? '') }}"
                                            class="w-full px-3 py-3 rounded-xl border-gray-300 focus:border-[#D4AF37] focus:ring-[#D4AF37] text-sm"
                                            placeholder="e.g. 43000" required>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-2">
                                            City
                                        </label>
                                        <input type="text" name="city"
                                            value="{{ old('city', $defaultAddress->city ?? '') }}"
                                            class="w-full px-3 py-3 rounded-xl border-gray-300 focus:border-[#D4AF37] focus:ring-[#D4AF37] text-sm"
                                            placeholder="e.g. Kajang" required>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-2">
                                            State
                                        </label>

                                        <select name="state"
                                            class="w-full px-3 py-3 rounded-xl border-gray-300
                                                   focus:border-[#D4AF37] focus:ring-[#D4AF37] text-sm"
                                            data-state-select required>

                                            <option value="">Select State</option>

                                            @foreach ($states as $s)
                                                <option value="{{ $s['name'] }}" data-zone="{{ $s['zone'] }}"
                                                    @selected(old('state', $defaultAddress->state ?? '') === $s['name'])>
                                                    {{ $s['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-2">
                                            Country
                                        </label>
                                        <input type="text" name="country"
                                            value="{{ old('country', $defaultAddress->country ?? 'Malaysia') }}"
                                            class="w-full px-3 py-3 rounded-xl border-gray-300 focus:border-[#D4AF37] focus:ring-[#D4AF37] text-sm"
                                            placeholder="e.g. Malaysia" required>
                                    </div>

                                </div>
                            </div>
                        </section>

                        {{-- Card 2：Payment Method --}}
                        <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
                            <h2 class="text-base font-semibold text-[#0A0A0C] mb-4">
                                Payment Method
                            </h2>

                            @php
                                $defaultCode = old(
                                    'payment_method',
                                    optional($paymentMethods->firstWhere('is_default', true))->code ??
                                        optional($paymentMethods->first())->code,
                                );
                            @endphp

                            <div class="space-y-5" id="payment-methods-container" data-default="{{ $defaultCode }}">
                                @foreach ($paymentMethods as $method)
                                    @php
                                        $isOnlineTransfer = $method->code === 'online_transfer';
                                    @endphp

                                    {{-- 🔶 ① 顶部 Payment Method 卡（单独一张） --}}
                                    <label
                                        class="rounded-2xl border border-gray-200 bg-[#FFF7EC] px-4 py-3 flex items-start gap-3 text-sm cursor-pointer hover:border-[#F97316] hover:bg-[#FFF0E0] transition">

                                        {{-- radio --}}
                                        <div class="mt-1">
                                            <input type="radio" name="payment_method"
                                                class="payment-radio h-4 w-4 text-[#F97316] border-gray-300 focus:ring-[#F97316]"
                                                value="{{ $method->code }}" @checked($defaultCode === $method->code)>
                                        </div>

                                        <div class="w-full flex items-center justify-between">
                                            <div>
                                                <p class="font-semibold text-gray-900 text-base">
                                                    {{ $method->name }}
                                                </p>

                                                @if ($method->short_description)
                                                    <p class="text-gray-600 text-sm mt-1">
                                                        {{ $method->short_description }}
                                                    </p>
                                                @endif
                                            </div>

                                        </div>
                                    </label>


                                    {{-- 🔷 ② 下方 Detail 卡（独立一张 · 可展开/收起） --}}
                                    <div class="payment-detail hidden" data-code="{{ $method->code }}">
                                        @if ($isOnlineTransfer)
                                            @php
                                                $amountToTransfer = $orderTotal ?? ($total ?? ($subtotal ?? 0));
                                            @endphp

                                            <div
                                                class="mt-3 bg-white rounded-2xl border border-gray-200 shadow-sm p-5 space-y-6">

                                                {{-- Payment Instructions --}}
                                                <div class="flex items-start gap-3">
                                                    <div
                                                        class="mt-1 h-8 w-8 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                            fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M11.25 11.25v5.25m0-8.25h.008v.008H11.25zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </div>

                                                    <div class="text-sm leading-relaxed">
                                                        <p class="font-semibold text-gray-900">Payment Instructions:
                                                        </p>
                                                        @if ($method->instructions)
                                                            <p class="text-gray-600 text-sm mt-1">
                                                                {{ $method->instructions }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Bank Transfer Details --}}
                                                <div>
                                                    <p class="text-base font-semibold text-gray-900 mb-2">Bank Transfer
                                                        Details</p>

                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                                        {{-- Account Number --}}
                                                        <div
                                                            class="rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3">
                                                            <p
                                                                class="text-xs font-semibold tracking-[0.14em] text-gray-500 uppercase">
                                                                Account Number
                                                            </p>
                                                            <p
                                                                class="mt-2 text-base font-bold tracking-wide text-gray-900">
                                                                {{ $method->bank_account_number }}
                                                            </p>
                                                        </div>

                                                        {{-- Account Name --}}
                                                        <div
                                                            class="rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3">
                                                            <p
                                                                class="text-xs font-semibold tracking-[0.14em] text-gray-500 uppercase">
                                                                Account Name
                                                            </p>
                                                            <p class="mt-2 text-base font-bold text-gray-900">
                                                                {{ $method->bank_account_name }}
                                                            </p>
                                                        </div>

                                                        {{-- Amount To Transfer --}}
                                                        <div
                                                            class="rounded-2xl border border-amber-300 bg-[#FFF4E0] px-4 py-3">
                                                            <p
                                                                class="text-xs font-semibold tracking-[0.14em] text-amber-700 uppercase">
                                                                Amount To Transfer
                                                            </p>
                                                            <p class="mt-2 text-2xl font-bold text-amber-800">
                                                                RM {{ number_format($amountToTransfer, 2) }}
                                                            </p>
                                                        </div>

                                                        {{-- Bank Name --}}
                                                        <div
                                                            class="rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3">
                                                            <p
                                                                class="text-xs font-semibold tracking-[0.14em] text-gray-500 uppercase">
                                                                Bank Name
                                                            </p>
                                                            <p class="mt-2 text-base font-bold text-gray-900">
                                                                {{ $method->bank_name }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- QR --}}
                                                @if ($method->duitnow_qr_path)
                                                    <div>
                                                        <p
                                                            class="text-xs font-semibold tracking-[0.14em] text-gray-500 uppercase mb-2">
                                                            Scan DuitNow QR
                                                        </p>
                                                        <div
                                                            class="inline-flex items-center justify-center rounded-2xl border border-gray-200 bg-white p-3 shadow-sm">
                                                            <img src="{{ asset('storage/' . $method->duitnow_qr_path) }}"
                                                                class="w-40 h-40 object-contain rounded-xl">
                                                        </div>
                                                    </div>
                                                @endif

                                                {{-- Upload --}}
                                                <div class="pt-2 border-t border-gray-100">
                                                    <label class="text-sm font-semibold text-gray-800">
                                                        Upload Payment Receipt
                                                        <span class="text-gray-500 ml-1">(Required)</span>
                                                    </label>

                                                    <input type="file" name="payment_receipt" required
                                                        class="mt-2 block w-full sm:w-72 text-sm border border-gray-300 rounded-xl file:mr-3 file:px-4 file:py-2 file:rounded-xl file:border-0 file:bg-[#FDF3D7] file:text-[#8f6a10] hover:file:bg-[#F9E6AE] file:focus:bg-[#FDF3D7] file:active:bg-[#F9E6AE]">
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>


                            @error('payment_method')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </section>
                    </div>

                    <div class="lg:col-span-2 space-y-4">
                        {{-- 右：Order Summary card --}}
                        <aside class="bg-[#F9F4E5] rounded-2xl border border-[#E5D9B6] p-5 h-max lg:sticky lg:top-28">
                            <h2 class="text-lg font-semibold text-[#0A0A0C] mb-4">
                                Order Summary
                            </h2>

                            {{-- 商品列表（迷你版 cart card） --}}
                            <div class="space-y-3 mb-4 max-h-72 overflow-y-auto pr-1">
                                @foreach ($items as $item)
                                    @php
                                        $p = $item->product;
                                    @endphp

                                    <div
                                        class="flex gap-3 border border-[#E5D9B6]/70 bg-white/60 rounded-2xl px-3 py-3 items-start">
                                        {{-- 小图 --}}
                                        <div
                                            class="w-16 h-16 sm:w-18 sm:h-18 rounded-xl bg-gray-100 overflow-hidden flex-shrink-0">
                                            @if ($p?->image)
                                                <img src="{{ asset('storage/' . $p->image) }}"
                                                    alt="{{ $p->name }}" class="w-full h-full object-cover">
                                            @else
                                                <div
                                                    class="w-full h-full flex items-center justify-center text-[10px] text-gray-400">
                                                    No image
                                                </div>
                                            @endif
                                        </div>

                                        {{-- 名称 + variant + qty + 小计 --}}
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between gap-2">
                                                <div class="min-w-0">
                                                    <p
                                                        class="text-[11px] uppercase tracking-[0.16em] text-gray-400 mb-0.5">
                                                        {{ $p->category->name ?? 'Product' }}
                                                    </p>
                                                    <h3 class="text-sm font-semibold text-gray-900 line-clamp-2">
                                                        {{ $p->name }}
                                                    </h3>

                                                    @if ($item->variant_label)
                                                        <p class="text-xs text-gray-500 mt-0.5">
                                                            {{ $item->variant_label }}
                                                        </p>
                                                    @endif

                                                    <p class="text-xs text-gray-400 mt-0.5">
                                                        Qty: {{ $item->qty }}
                                                    </p>
                                                </div>

                                                <div class="text-right">
                                                    <p class="text-sm font-semibold text-[#8f6a10]">
                                                        RM {{ number_format($item->unit_price * $item->qty, 2) }}
                                                    </p>
                                                    <p class="text-[11px] text-gray-400">
                                                        RM {{ number_format($item->unit_price, 2) }} / pc
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- 小计 / 运费 / 总额 --}}
                            <dl class="space-y-2 text-base">
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Subtotal</dt>
                                    <dd class="font-semibold text-gray-900">
                                        RM {{ number_format($subtotal, 2) }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Shipping Fee</dt>
                                    <dd class="text-gray-700" data-shipping-text>
                                        @if (!$hasPhysical)
                                            Digital Product (Free)
                                        @else
                                            To be confirmed
                                        @endif
                                    </dd>
                                </div>
                            </dl>

                            <div class="border-t border-[#E5D9B6] my-4"></div>

                            <div class="flex justify-between items-center mb-4 text-base">
                                <span class="font-semibold text-gray-900">Total</span>

                                <span class="text-lg font-semibold text-[#8f6a10]" data-total-text>
                                    {{-- 默认先显示 Subtotal --}}
                                    RM {{ number_format($subtotal, 2) }}
                                </span>
                            </div>


                            {{-- Desktop：按钮放在右边 card 里 --}}
                            <button type="submit"
                                class="lg:inline-flex w-full items-center justify-center px-4 py-2.5 rounded-full bg-[#D4AF37] text-white text-base font-semibold shadow hover:brightness-110 transition">
                                Place Order
                            </button>

                            <p class="mt-3 text-sm text-gray-500">
                                Secure checkout · All prices in RM
                            </p>

                        </aside>
                    </div>
                </section>
            </form>
        </div>
    </div>

    <style>
        .no-scrollbar {
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE/Edge */
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
            /* Chrome / Safari */
        }

        [data-address-scroller] {
            cursor: grab;
        }

        .cursor-grabbing {
            cursor: grabbing !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ------- 1) 点击地址卡 -> 填表单 -------
            const buttons = document.querySelectorAll('[data-address-choice]');

            const nameInput = document.querySelector('input[name="name"]');
            const phoneInput = document.querySelector('input[name="phone"]');
            const emailInput = document.querySelector('input[name="email"]');
            const line1Input = document.querySelector('input[name="address_line1"]');
            const line2Input = document.querySelector('input[name="address_line2"]');
            const postcodeInput = document.querySelector('input[name="postcode"]');
            const cityInput = document.querySelector('input[name="city"]');
            const stateSelect = document.querySelector('[data-state-select]');
            const countryInput = document.querySelector('input[name="country"]');

            if (buttons.length) {
                buttons.forEach(btn => {
                    btn.addEventListener('click', () => {
                        if (nameInput) nameInput.value = btn.dataset.name || '';
                        if (phoneInput) phoneInput.value = btn.dataset.phone || '';
                        if (emailInput) emailInput.value = btn.dataset.email || '';
                        if (line1Input) line1Input.value = btn.dataset.address_line1 || '';
                        if (line2Input) line2Input.value = btn.dataset.address_line2 || '';
                        if (postcodeInput) postcodeInput.value = btn.dataset.postcode || '';
                        if (cityInput) cityInput.value = btn.dataset.city || '';
                        if (countryInput) countryInput.value = btn.dataset.country || '';
                        // 👇 处理 state dropdown
                        if (stateSelect) {
                            const stateName = btn.dataset.state || '';

                            // 尝试匹配 option 的 value（你的 option value 是州名）
                            let found = false;
                            Array.from(stateSelect.options).forEach(opt => {
                                if (opt.value === stateName) {
                                    found = true;
                                }
                            });

                            if (found) {
                                stateSelect.value = stateName;
                                // 触发一次 change，让运费 + Total 跟着更新
                                stateSelect.dispatchEvent(new Event('change'));
                            } else {
                                // 找不到匹配，就清空 & 维持 To be confirmed
                                stateSelect.value = '';
                                stateSelect.dispatchEvent(new Event('change'));
                            }
                        }

                        // 高亮当前选中
                        buttons.forEach(b => {
                            b.classList.remove('border-[#D4AF37]', 'bg-[#FDF7E7]');
                            b.classList.add('border-gray-200', 'bg-gray-50');
                        });
                        btn.classList.remove('border-gray-200', 'bg-gray-50');
                        btn.classList.add('border-[#D4AF37]', 'bg-[#FDF7E7]');
                    });
                });
            }

            // ------- 2) 水平滚动 (拖动 / 触屏 ONLY) -------
            const scroller = document.querySelector('[data-address-scroller]');
            if (!scroller) return;

            // Pointer 拖动（支持鼠标 + 触屏）⚠️ 不拦截 click
            let isDown = false;
            let startX;
            let startScrollLeft;

            scroller.addEventListener('pointerdown', function(e) {
                isDown = true;
                scroller.classList.add('cursor-grabbing');
                startX = e.clientX;
                startScrollLeft = scroller.scrollLeft;
            });

            scroller.addEventListener('pointermove', function(e) {
                if (!isDown) return;
                const dx = e.clientX - startX;
                scroller.scrollLeft = startScrollLeft - dx;
            });

            function stopDrag() {
                isDown = false;
                scroller.classList.remove('cursor-grabbing');
            }

            scroller.addEventListener('pointerup', stopDrag);
            scroller.addEventListener('pointercancel', stopDrag);
            scroller.addEventListener('pointerleave', stopDrag);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('payment-methods-container');
            if (!container) return;

            const radios = container.querySelectorAll('.payment-radio');
            const details = container.querySelectorAll('.payment-detail');

            function refreshPaymentDetails() {
                const checked = container.querySelector('.payment-radio:checked');
                const activeCode = checked ? checked.value : null;

                details.forEach(detail => {
                    if (detail.dataset.code === activeCode) {
                        detail.classList.remove('hidden');
                    } else {
                        detail.classList.add('hidden');
                    }
                });
            }

            radios.forEach(r => {
                r.addEventListener('change', refreshPaymentDetails);
            });

            // 初始化：默认选中的那一个展开
            refreshPaymentDetails();
        });
    </script>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const method = document.querySelector('.payment-radio:checked')?.value;
            const file = document.querySelector('input[name="payment_receipt"]');

            if (method === 'online_transfer' && !file.value) {
                e.preventDefault();
                alert('Please upload your payment receipt before placing order.');
                file.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                file.focus();
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const stateSelect = document.querySelector('[data-state-select]');
            const shippingText = document.querySelector('[data-shipping-text]');
            const totalText = document.querySelector('[data-total-text]');
            const hasPhysical = @json($hasPhysical);
            const shippingRates = @json($shippingRates); // { west_my: 8, east_my: 15, ... }
            const subtotal = {{ $subtotal }}; // 纯数字

            if (!stateSelect || !shippingText || !totalText) return;

            // 全部 digital → 永远 Free，Total = subtotal
            if (!hasPhysical) {
                shippingText.textContent = 'Digital Product (Free)';
                totalText.textContent = 'RM ' + subtotal.toFixed(2);
                return;
            }

            function updateShipping() {
                const selected = stateSelect.selectedOptions[0];
                const zone = selected ? selected.dataset.zone : null;

                // 还没选 / 没有 zone → 待确认
                if (!zone) {
                    shippingText.textContent = 'To be confirmed';
                    totalText.textContent = 'RM ' + subtotal.toFixed(2);
                    return;
                }

                const fee = Number(shippingRates[zone] ?? 0);

                if (fee === 0) {
                    shippingText.textContent = 'Free';
                    totalText.textContent = 'RM ' + subtotal.toFixed(2);
                } else {
                    shippingText.textContent = 'RM ' + fee.toFixed(2);
                    totalText.textContent = 'RM ' + (subtotal + fee).toFixed(2);
                }
            }

            stateSelect.addEventListener('change', updateShipping);

            // 页面加载时根据默认 state 算一次
            updateShipping();
        });
    </script>





</x-app-layout>
