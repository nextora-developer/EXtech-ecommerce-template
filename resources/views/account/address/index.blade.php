<x-app-layout>
    <div class="bg-[#f7f7f9] min-h-screen py-10">
        <div class="max-w-7xl5 mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-xs font-medium uppercase tracking-widest text-gray-400 mb-8">
                <a href="{{ route('home') }}" class="hover:text-[#8f6a10] transition-colors">Home</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span class="text-gray-900">Shipping Addresses</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                {{-- Left Sidebar --}}
                <aside class="lg:col-span-1">
                    @include('account.partials.sidebar')
                </aside>

                {{-- Right Content --}}
                <main class="lg:col-span-3 space-y-6">

                    {{-- Header Section --}}
                    <section class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                            <div>
                                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Shipping Addresses</h1>
                                <p class="text-gray-500 mt-2">Manage your delivery locations for a faster checkout
                                    experience.</p>
                            </div>
                            <a href="{{ route('account.address.create') }}"
                                class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-black text-white text-sm font-bold shadow-lg hover:bg-gray-800 transition-all active:scale-95">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 4v16m8-8H4" stroke-width="2.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                Add New Address
                            </a>
                        </div>
                    </section>

                    {{-- Address Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse ($addresses as $address)
                            <div
                                class="group relative bg-white rounded-[2rem] border border-gray-100 p-6 hover:border-[#D4AF37]/40 hover:shadow-xl hover:shadow-orange-900/5 transition-all duration-500">

                                {{-- Default Badge --}}
                                @if ($address->is_default)
                                    <div class="absolute top-6 right-6">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100">
                                            Default
                                        </span>
                                    </div>
                                @endif

                                {{-- Address Info --}}
                                <div class="space-y-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-[#8f6a10]">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-gray-900">
                                                {{ $address->recipient_name ?? $user->name }}</h3>
                                            <p class="text-xs text-gray-400 font-medium tracking-wide">
                                                {{ $address->phone }}</p>
                                        </div>
                                    </div>

                                    <div class="pl-1 text-sm text-gray-600 leading-relaxed min-h-[80px]">
                                        <p>{{ $address->address_line1 }}</p>
                                        @if ($address->address_line2)
                                            <p>{{ $address->address_line2 }}</p>
                                        @endif
                                        <p>{{ $address->postcode }} {{ $address->city }}</p>
                                        <p class="uppercase text-[11px] font-bold text-gray-400 mt-1 tracking-widest">
                                            {{ $address->state }}, {{ $address->country ?? 'Malaysia' }}
                                        </p>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="pt-5 border-t border-gray-50 flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <a href="{{ route('account.address.edit', $address) }}"
                                                class="inline-flex mt-1 items-center text-xs font-black uppercase tracking-widest text-gray-400 hover:text-[#8f6a10] transition-colors">
                                                Edit
                                            </a>

                                            <form action="{{ route('account.address.destroy', $address) }}"
                                                method="POST" onsubmit="return confirm('Delete this address?');">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center text-xs font-black uppercase tracking-widest text-gray-400 hover:text-red-500 transition-colors">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>


                                        @unless ($address->is_default)
                                            <form action="{{ route('account.address.set-default', $address) }}"
                                                method="POST">
                                                @csrf @method('PUT')
                                                <button
                                                    class="text-[10px] font-black uppercase tracking-widest text-[#8f6a10] hover:text-black transition-colors">
                                                    Set as Primary
                                                </button>
                                            </form>
                                        @endunless
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div
                                class="col-span-full bg-white rounded-[2rem] border border-dashed border-gray-200 p-16 text-center">
                                <div
                                    class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                            stroke-width="1.5" />
                                        <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="1.5" />
                                    </svg>
                                </div>
                                <p class="text-gray-500 font-medium">You haven't saved any addresses yet.</p>
                            </div>
                        @endforelse
                    </div>

                </main>
            </div>
        </div>
    </div>
</x-app-layout>
