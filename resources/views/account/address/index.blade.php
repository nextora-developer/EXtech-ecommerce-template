{{-- resources/views/account/address/index.blade.php --}}
<x-app-layout>
    <div class="bg-[#f7f7f9] py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <nav class="text-sm text-gray-500 mb-4">
                <a href="{{ route('home') }}" class="hover:text-[#8f6a10]">Home</a>
                <span class="mx-1">/</span>
                <span class="text-gray-400">Addresses</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

                {{-- 左侧 Sidebar --}}
                <aside class="lg:col-span-1">
                    @include('account.partials.sidebar')
                </aside>

                {{-- 右侧内容 --}}
                <main class="lg:col-span-3 space-y-5">

                    {{-- Card: Address List --}}
                    <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">

                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h2 class="text-lg font-semibold text-[#0A0A0C]">
                                    My Addresses
                                </h2>
                                <p class="text-sm text-gray-500 mt-1">
                                    Manage your shipping / delivery addresses.
                                </p>
                            </div>

                            <a href="{{ route('account.address.create') }}"
                                class="inline-flex items-center px-4 py-2 rounded-full bg-[#D4AF37] text-white text-sm font-semibold shadow hover:brightness-110 transition">
                                + Add New Address
                            </a>
                        </div>

                        @forelse ($addresses as $address)
                            <div
                                class="rounded-xl border border-gray-200 px-5 py-4 mb-4 bg-gray-50 text-base hover:bg-[#FFF9E6] hover:border-[#D4AF37]/50 transition">

                                <div class="flex items-start justify-between gap-6">

                                    {{-- 左：地址详情 --}}
                                    <div class="space-y-2">
                                        {{-- 收件人 + 电话 --}}
                                        <div class="flex items-center gap-3">
                                            <p class="font-medium text-[#0A0A0C] text-base">
                                                {{ $address->recipient_name ?? $user->name }}
                                            </p>

                                            @if ($address->is_default)
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-[#D4AF37]/10 text-[#8f6a10]">
                                                    Default
                                                </span>
                                            @endif
                                        </div>

                                        @if ($address->phone)
                                            <p class="text-sm text-gray-600">
                                                📞 {{ $address->phone }}
                                            </p>
                                        @endif

                                        {{-- 地址文本 --}}
                                        <p class="text-sm text-gray-600 leading-snug">
                                            {{ $address->address_line1 }}<br>
                                            @if ($address->address_line2)
                                                {{ $address->address_line2 }}<br>
                                            @endif
                                            {{ $address->postcode }} {{ $address->city }}<br>
                                            {{ $address->state }},
                                            {{ $address->country ?? 'Malaysia' }}
                                        </p>
                                    </div>

                                    {{-- 右操作按钮 --}}
                                    <div class="flex flex-col items-end gap-3 text-sm">

                                        @unless ($address->is_default)
                                            <form action="{{ route('account.address.set-default', $address) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')

                                                <button
                                                    class="inline-flex items-center px-3 py-1 rounded-full bg-[#D4AF37]/10 text-[#8f6a10] text-xs font-medium hover:bg-[#D4AF37]/20 transition">
                                                    Set as default
                                                </button>
                                            </form>
                                        @endunless

                                        <div class="flex items-center gap-2 text-sm text-gray-400">

                                            {{-- Edit --}}
                                            <a href="{{ route('account.address.edit', $address) }}"
                                                class="hover:text-[#8f6a10]">
                                                Edit
                                            </a>

                                            <span class="text-gray-300">•</span>

                                            {{-- Delete --}}
                                            <form action="{{ route('account.address.destroy', $address) }}"
                                                method="POST" onsubmit="return confirm('Delete this address?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="hover:text-red-600">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @empty
                            <p class="text-base text-gray-500">
                                You haven't added any address yet.
                            </p>
                        @endforelse
                    </section>

                </main>
            </div>
        </div>
    </div>
</x-app-layout>
