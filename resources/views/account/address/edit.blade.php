<x-app-layout>
    <div class="bg-[#f7f7f9] min-h-screen py-10">
        <div class="max-w-7xl5 mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-xs font-medium uppercase tracking-widest text-gray-400 mb-8">
                <a href="{{ route('home') }}" class="hover:text-[#8f6a10] transition-colors">Home</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <a href="{{ route('account.address.index') }}"
                    class="hover:text-[#8f6a10] transition-colors">Shipping Addresses</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span class="text-gray-900">Edit</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

                {{-- Sidebar --}}
                <aside class="lg:col-span-1">
                    @include('account.partials.sidebar')
                </aside>

                {{-- Right content --}}
                <main class="lg:col-span-3 space-y-5">

                    {{-- Card: Form --}}
                    <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">

                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h2 class="text-lg font-semibold text-[#0A0A0C]">
                                    Edit Address
                                </h2>
                                <p class="text-sm text-gray-500 mt-1">
                                    Update your shipping / delivery address details.
                                </p>
                            </div>
                        </div>

                        {{-- Validation errors --}}
                        @if ($errors->any())
                            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                <p class="font-semibold mb-1">There were some problems with your input:</p>
                                <ul class="list-disc list-inside space-y-0.5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('account.address.update', $address) }}" class="space-y-5">
                            @csrf
                            @method('PUT')

                            {{-- Row: Recipient + Phone --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                <div>
                                    <label class="block text-sm text-gray-500 mb-1">
                                        Recipient Name
                                    </label>
                                    <input type="text" name="recipient_name"
                                        value="{{ old('recipient_name', $address->recipient_name ?? $user->name) }}"
                                        class="w-full rounded-xl border-gray-200 text-base px-3 py-3
                                              focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                                </div>

                                <div>
                                    <label class="block text-sm text-gray-500 mb-1">
                                        Phone Number
                                    </label>
                                    <input type="text" name="phone" value="{{ old('phone', $address->phone) }}"
                                        class="w-full rounded-xl border-gray-200 text-base px-3 py-3
                                              focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                                </div>

                                <div>
                                    <label class="block text-sm text-gray-500 mb-1">
                                        Email Address
                                    </label>
                                    <input type="email" name="email" value="{{ old('phone', $address->email) }}"
                                        class="w-full rounded-xl border-gray-200 text-base px-3 py-3
                                            focus:border-[#D4AF37] focus:ring-[#D4AF37]/30"
                                        placeholder="name@example.com">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                <div>
                                    <label class="block text-sm text-gray-500 mb-1">
                                        Address Line 1
                                    </label>
                                    <input type="text" name="address_line1"
                                        value="{{ old('address_line1', $address->address_line1) }}"
                                        class="w-full rounded-xl border-gray-200 text-base px-3 py-3
                                              focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                                </div>

                                <div>
                                    <label class="block text-sm text-gray-500 mb-1">
                                        Address Line 2 <span class="text-gray-400 text-sm">(optional)</span>
                                    </label>
                                    <input type="text" name="address_line2"
                                        value="{{ old('address_line2', $address->address_line2) }}"
                                        class="w-full rounded-xl border-gray-200 text-base px-3 py-3
                                              focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                                </div>
                            </div>

                            {{-- Row: Postcode, City, State, Country --}}
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">

                                <div>
                                    <label class="block text-sm text-gray-500 mb-1">
                                        Postcode
                                    </label>
                                    <input type="text" name="postcode"
                                        value="{{ old('postcode', $address->postcode) }}"
                                        class="w-full rounded-xl border-gray-200 text-base px-3 py-3
                                              focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                                </div>

                                <div>
                                    <label class="block text-sm text-gray-500 mb-1">
                                        City
                                    </label>
                                    <input type="text" name="city" value="{{ old('city', $address->city) }}"
                                        class="w-full rounded-xl border-gray-200 text-base px-3 py-3
                                              focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                                </div>

                                <div>
                                    <label class="block text-sm text-gray-500 mb-1">
                                        State
                                    </label>

                                    <select name="state"
                                        class="w-full rounded-xl border-gray-200 text-base px-3 py-3
                                                focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">

                                        <option value="">Select State</option>

                                        @foreach ($states as $s)
                                            <option value="{{ $s['name'] }}" @selected(old('state', $address->state ?? '') === $s['name'])>
                                                {{ $s['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm text-gray-500 mb-1">
                                        Country
                                    </label>
                                    <input type="text" name="country"
                                        value="{{ old('country', $address->country ?? 'Malaysia') }}"
                                        class="w-full rounded-xl border-gray-200 text-base px-3 py-3
                                              focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                                </div>
                            </div>

                            {{-- Default address toggle --}}
                            <div class="flex items-center justify-between pt-4">
                                <label class="inline-flex items-center gap-2 text-base text-gray-600">
                                    <input type="checkbox" name="is_default" value="1"
                                        class="rounded border-gray-300 text-[#D4AF37] focus:ring-[#D4AF37]/40"
                                        {{ old('is_default', $address->is_default) ? 'checked' : '' }}>
                                    <span>Set as my default address</span>
                                </label>
                            </div>

                            <div class="pt-5 flex items-center gap-4">
                                <button type="submit"
                                    class="px-7 py-3 rounded-full bg-[#D4AF37] text-white text-base font-semibold shadow hover:brightness-110 transition">
                                    Save Changes
                                </button>

                                <a href="{{ route('account.address.index') }}"
                                    class="px-7 py-3 rounded-full bg-gray-200 text-gray-700 text-base font-medium hover:bg-gray-300 transition">
                                    Cancel
                                </a>
                            </div>

                        </form>
                    </section>
                </main>

            </div>
        </div>
    </div>
</x-app-layout>
