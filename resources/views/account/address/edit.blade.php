<x-app-layout>
    <div class="bg-[#f7f7f9] py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <nav class="text-xs text-gray-500 mb-4">
                <a href="{{ route('home') }}" class="hover:text-[#8f6a10]">Home</a>
                <span class="mx-1">/</span>
                <a href="{{ route('account.address.index') }}" class="hover:text-[#8f6a10]">My Addresses</a>
                <span class="mx-1">/</span>
                <span class="text-gray-400">Edit</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

                {{-- Sidebar --}}
                <aside class="lg:col-span-1">
                    @include('account.partials.sidebar')
                </aside>

                {{-- Right content --}}
                <main class="lg:col-span-3 space-y-5">

                    {{-- Back --}}
                    {{-- <div class="mb-2">
                        <a href="{{ route('account.address.index') }}"
                            class="inline-flex items-center text-xs text-gray-500 hover:text-[#8f6a10]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                            Back to addresses
                        </a>
                    </div> --}}

                    {{-- Card: Form --}}
                    <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h2 class="text-base font-semibold text-[#0A0A0C]">
                                    Edit Address
                                </h2>
                                <p class="text-xs text-gray-500 mt-1">
                                    Update your shipping / delivery address details.
                                </p>
                            </div>
                        </div>

                        {{-- Validation errors --}}
                        @if ($errors->any())
                            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-xs text-red-700">
                                <p class="font-semibold mb-1">There were some problems with your input:</p>
                                <ul class="list-disc list-inside space-y-0.5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('account.address.update', $address) }}" class="space-y-4">
                            @csrf
                            @method('PUT')

                            {{-- Row: Recipient + Phone --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">
                                        Recipient Name
                                    </label>
                                    <input type="text" name="recipient_name"
                                        value="{{ old('recipient_name', $address->recipient_name ?? $user->name) }}"
                                        class="w-full rounded-xl border-gray-200 text-sm px-3 py-2.5
                                                  focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                                </div>

                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">
                                        Phone Number
                                    </label>
                                    <input type="text" name="phone" value="{{ old('phone', $address->phone) }}"
                                        class="w-full rounded-xl border-gray-200 text-sm px-3 py-2.5
                                                  focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Address line 1 --}}
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">
                                        Address Line 1
                                    </label>
                                    <input type="text" name="address_line1"
                                        value="{{ old('address_line1', $address->address_line1) }}"
                                        class="w-full rounded-xl border-gray-200 text-sm px-3 py-2.5
                                              focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                                </div>

                                {{-- Address line 2 --}}
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">
                                        Address Line 2 <span class="text-gray-400">(optional)</span>
                                    </label>
                                    <input type="text" name="address_line2"
                                        value="{{ old('address_line2', $address->address_line2) }}"
                                        class="w-full rounded-xl border-gray-200 text-sm px-3 py-2.5
                                              focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                                </div>
                            </div>

                            {{-- Row: Postcode + City --}}
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">
                                        Postcode
                                    </label>
                                    <input type="text" name="postcode"
                                        value="{{ old('postcode', $address->postcode) }}"
                                        class="w-full rounded-xl border-gray-200 text-sm px-3 py-2.5
                                                  focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                                </div>

                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">
                                        City
                                    </label>
                                    <input type="text" name="city" value="{{ old('city', $address->city) }}"
                                        class="w-full rounded-xl border-gray-200 text-sm px-3 py-2.5
                                                  focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                                </div>

                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">
                                        State
                                    </label>
                                    <input type="text" name="state" value="{{ old('state', $address->state) }}"
                                        class="w-full rounded-xl border-gray-200 text-sm px-3 py-2.5
                                                  focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                                </div>

                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">
                                        Country
                                    </label>
                                    <input type="text" name="country"
                                        value="{{ old('country', $address->country ?? 'Malaysia') }}"
                                        class="w-full rounded-xl border-gray-200 text-sm px-3 py-2.5
                                                  focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                                </div>
                            </div>

                            {{-- Default address toggle --}}
                            <div class="flex items-center justify-between pt-5">
                                <label class="inline-flex items-center gap-2 text-xs text-gray-600">
                                    <input type="checkbox" name="is_default" value="1"
                                        class="rounded border-gray-300 text-[#D4AF37] focus:ring-[#D4AF37]/40"
                                        {{ old('is_default', $address->is_default) ? 'checked' : '' }}>
                                    <span>Set as my default address</span>
                                </label>
                            </div>

                            <div class="pt-4 flex items-center gap-3">
                                <button type="submit"
                                    class="px-6 py-2.5 rounded-full bg-[#D4AF37] text-white text-sm font-semibold shadow hover:brightness-110 transition">
                                    Save Changes
                                </button>

                                <a href="{{ route('account.address.index') }}"
                                    class="px-6 py-2.5 rounded-full bg-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-300 transition">
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
