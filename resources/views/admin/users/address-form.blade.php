@extends('admin.layouts.app')

@section('content')
    {{-- Header Section --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-semibold text-gray-900 tracking-tight">
                {{ $address->exists ? 'Edit Address' : 'Create New Address' }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Assigning to: <span class="font-bold text-[#8f6a10]">{{ $user->name }}</span> ({{ $user->email }})
            </p>
        </div>

        <a href="{{ route('admin.users.edit', $user) }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200
                   text-sm font-semibold text-gray-600 hover:bg-gray-50 transition shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            <span>Back</span>
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-red-500">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"
                    clip-rule="evenodd" />
            </svg>
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST"
        action="{{ $address->exists ? route('admin.addresses.update', $address) : route('admin.addresses.store', $user) }}"
        class="bg-white border border-[#D4AF37]/18 rounded-2xl p-8 shadow-[0_18px_40px_rgba(0,0,0,0.06)] w-full">

        @csrf
        @if ($address->exists)
            @method('PUT')
        @endif

        <div class="space-y-8">
            {{-- Section: Recipient Details --}}
            <div>
                <div class="flex items-center gap-2 mb-5">
                    <span class="w-1.5 h-6 bg-[#D4AF37] rounded-full"></span>
                    <h2 class="font-bold text-gray-900">Recipient Information</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="text-xs uppercase font-black tracking-widest text-gray-400">Recipient Name</label>
                        <input type="text" name="recipient_name"
                            value="{{ old('recipient_name', $address->recipient_name) }}" placeholder="e.g. Tan Mei Ling"
                            class="mt-1.5 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 font-medium">
                    </div>

                    <div>
                        <label class="text-xs uppercase font-black tracking-widest text-gray-400">Contact Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $address->phone) }}"
                            placeholder="e.g. 012-3456789"
                            class="mt-1.5 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 font-medium">
                    </div>

                    <div>
                        <label class="text-xs uppercase font-black tracking-widest text-gray-400 text-gray-300">Email
                            (Optional)</label>
                        <input type="email" name="email" value="{{ old('email', $address->email) }}"
                            placeholder="e.g. you@example.com"
                            class="mt-1.5 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 font-medium">
                    </div>
                </div>
            </div>

            {{-- Section: Address Details --}}
            <div class="border-t border-gray-100 pt-8">
                <div class="flex items-center gap-2 mb-5">
                    <span class="w-1.5 h-6 bg-[#D4AF37] rounded-full"></span>
                    <h2 class="font-bold text-gray-900">Delivery Address</h2>
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-xs uppercase font-black tracking-widest text-gray-400">Address Line
                                1</label>
                            <input type="text" name="address_line1"
                                value="{{ old('address_line1', $address->address_line1) }}"
                                placeholder="House No, Street, Building Name"
                                class="mt-1.5 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 font-medium">
                        </div>

                        <div>
                            <label class="text-xs uppercase font-black tracking-widest text-gray-300">Address Line
                                2</label>
                            <input type="text" name="address_line2"
                                value="{{ old('address_line2', $address->address_line2) }}"
                                placeholder="Apartment, Unit, Floor (Optional)"
                                class="mt-1.5 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 font-medium">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
                        <div>
                            <label class="text-xs uppercase font-black tracking-widest text-gray-400">Postcode</label>
                            <input type="text" name="postcode" value="{{ old('postcode', $address->postcode) }}"
                                placeholder="e.g. 47000"
                                class="mt-1.5 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 font-medium">
                        </div>

                        <div>
                            <label class="text-xs uppercase font-black tracking-widest text-gray-400">City</label>
                            <input type="text" name="city" value="{{ old('city', $address->city) }}"
                                placeholder="e.g. Petaling Jaya"
                                class="mt-1.5 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 font-medium">
                        </div>

                        <div>
                            <label class="text-xs uppercase font-black tracking-widest text-gray-400">State</label>
                            <select name="state"
                                class="mt-1.5 w-full rounded-xl border-gray-200 font-medium focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                                <option value="">Select State</option>
                                @foreach ($states as $s)
                                    <option value="{{ $s['name'] }}" @selected(old('state', $address->state ?? '') === $s['name'])>
                                        {{ $s['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-xs uppercase font-black tracking-widest text-gray-400">Country</label>
                            <input type="text" name="country"
                                value="{{ old('country', $address->country ?? 'Malaysia') }}"
                                class="mt-1.5 w-full rounded-xl border-gray-200 bg-gray-50 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 font-semibold">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer Actions --}}
        <div class="mt-10 pt-8 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <label class="flex items-center gap-3 cursor-pointer group">
                <input type="checkbox" name="is_default" value="1"
                    class="w-5 h-5 rounded border-gray-300 text-[#D4AF37] focus:ring-[#D4AF37]/40 transition"
                    {{ old('is_default', $address->is_default ?? false) ? 'checked' : '' }}>
                <span class="text-sm font-bold text-gray-600 group-hover:text-[#8f6a10] transition">Set as default delivery
                    address</span>
            </label>

            <div class="flex gap-3">
                <a href="{{ route('admin.users.edit', $user) }}"
                    class="px-6 py-2.5 rounded-xl border border-gray-200 text-sm font-bold text-gray-500 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button
                    class="px-8 py-2.5 rounded-xl bg-[#D4AF37] text-white text-sm font-bold hover:bg-[#c29c2f] transition shadow-lg shadow-[#D4AF37]/20">
                    {{ $address->exists ? 'Update Address' : 'Save Address' }}
                </button>
            </div>
        </div>
    </form>
@endsection
