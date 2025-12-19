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

        <a href="{{ route('admin.users.edit', $user) }}" class="text-sm text-gray-600 hover:text-[#8f6a10]">
            ← Back to user
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

        {{-- Row 1: 4 columns --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="text-xs text-gray-500">Recipient name</label>
                <input type="text" name="recipient_name"
                    value="{{ old('recipient_name', $address->recipient_name) }}"
                    class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
            </div>

            <div>
                <label class="text-xs text-gray-500">Phone</label>
                <input type="text" name="phone"
                    value="{{ old('phone', $address->phone) }}"
                    class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
            </div>
        </div>


        {{-- Row 2: 4 columns --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="text-xs text-gray-500">Address line 1</label>
                <input type="text" name="address_line1"
                    value="{{ old('address_line1', $address->address_line1) }}"
                    class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
            </div>

            <div>
                <label class="text-xs text-gray-500">Address line 2 (optional)</label>
                <input type="text" name="address_line2"
                    value="{{ old('address_line2', $address->address_line2) }}"
                    class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
            </div>
        </div>


        {{-- Row 3: 4 columns --}}
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div>
                <label class="text-xs text-gray-500">Postcode</label>
                <input type="text" name="postcode"
                    value="{{ old('postcode', $address->postcode) }}"
                    class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
            </div>

            <div>
                <label class="text-xs text-gray-500">City</label>
                <input type="text" name="city"
                    value="{{ old('city', $address->city) }}"
                    class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
            </div>

            <div>
                <label class="text-xs text-gray-500">State</label>
                <input type="text" name="state"
                    value="{{ old('state', $address->state) }}"
                    class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
            </div>

            <div>
                <label class="text-xs text-gray-500">Country</label>
                <input type="text" name="country"
                    value="{{ old('country', $address->country ?? 'Malaysia') }}"
                    class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30">
            </div>
        </div>

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
