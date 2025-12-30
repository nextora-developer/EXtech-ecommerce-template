@extends('admin.layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-semibold">
                {{ $address->exists ? 'Edit address' : 'Add address' }}
            </h1>
            <p class="text-sm text-gray-500">
                For user: {{ $user->name }} ({{ $user->email }})
            </p>
        </div>

        <a href="{{ route('admin.users.edit', $user) }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200
          hover:bg-gray-50 transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"
                class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
            </svg>
            <span>Back</span>
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST"
        action="{{ $address->exists ? route('admin.addresses.update', $address) : route('admin.addresses.store', $user) }}"
        class="bg-white rounded-2xl border p-5 w-full">

        @csrf
        @if ($address->exists)
            @method('PUT')
        @endif

        <div class="space-y-6 text-sm">

            {{-- Row 1: 3 columns --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="text-xs text-gray-500">Recipient name</label>
                    <input type="text" name="recipient_name"
                        value="{{ old('recipient_name', $address->recipient_name) }}" placeholder="e.g. Tan Mei Ling"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                </div>

                <div>
                    <label class="text-xs text-gray-500">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $address->phone) }}"
                        placeholder="e.g. 012-3456789"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                </div>

                <div>
                    <label class="text-xs text-gray-500">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $address->email) }}"
                        placeholder="e.g. you@example.com"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                </div>
            </div>

            {{-- Row 2: 2 columns --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs text-gray-500">Address line 1</label>
                    <input type="text" name="address_line1" value="{{ old('address_line1', $address->address_line1) }}"
                        placeholder="House / Street / Building"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                </div>

                <div>
                    <label class="text-xs text-gray-500">Address line 2 (optional)</label>
                    <input type="text" name="address_line2" value="{{ old('address_line2', $address->address_line2) }}"
                        placeholder="Apartment / Unit / Floor (optional)"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                </div>
            </div>

            {{-- Row 3: 4 columns --}}
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label class="text-xs text-gray-500">Postcode</label>
                    <input type="text" name="postcode" value="{{ old('postcode', $address->postcode) }}"
                        placeholder="e.g. 47000"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                </div>

                <div>
                    <label class="text-xs text-gray-500">City</label>
                    <input type="text" name="city" value="{{ old('city', $address->city) }}"
                        placeholder="e.g. Petaling Jaya"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                </div>

                <div>
                    <label class="text-xs text-gray-500">State</label>
                    <select name="state"
                        class="mt-1 w-full rounded-xl border-gray-200 text-base px-3 py-3
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
                    <label class="text-xs text-gray-500">Country</label>
                    <input type="text" name="country" value="{{ old('country', $address->country ?? 'Malaysia') }}"
                        placeholder="e.g. Malaysia"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
                </div>
            </div>

        </div>

        {{-- Default address toggle --}}
        <div class="flex items-center justify-between pt-4">
            <label class="inline-flex items-center gap-2 text-base text-gray-600">
                <input type="checkbox" name="is_default" value="1"
                    class="rounded border-gray-300 text-[#D4AF37] focus:ring-[#D4AF37]/40"
                    {{ old('is_default', $address->is_default ?? false) ? 'checked' : '' }}>
                <span>Set as my default address</span>
            </label>
        </div>

        <div class="mt-6 flex gap-3">
            <button class="px-5 py-2 rounded-xl bg-[#D4AF37] text-white text-sm font-semibold hover:bg-[#c29c2f]">
                Save address
            </button>
            <a href="{{ route('admin.users.edit', $user) }}"
                class="px-5 py-2 rounded-xl border border-gray-300 text-sm hover:bg-gray-50">
                Cancel
            </a>
        </div>
    </form>
@endsection
