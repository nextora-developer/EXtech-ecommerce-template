@extends('admin.layouts.app')

@section('content')
    <div class="flex items-start justify-between mb-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">
                {{ $rate->exists ? 'Edit Shipping Rate' : 'New Shipping Rate' }}
            </h1>
            <p class="text-sm text-gray-500">Configure shipping fee for this zone</p>
        </div>

        <a href="{{ route('admin.shipping.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200
          hover:bg-gray-50 transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"
                class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
            </svg>
            <span>Back</span>
        </a>
    </div>

    <form method="POST" action="{{ $rate->exists ? route('admin.shipping.update', $rate) : route('admin.shipping.store') }}"
        class="bg-white rounded-2xl border border-[#D4AF37]/18 p-5 max-w-3xl shadow-[0_18px_40px_rgba(0,0,0,0.06)]">

        @csrf
        @if ($rate->exists)
            @method('PUT')
        @endif

        <div class="space-y-4">

            {{-- Name + Code --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                {{-- Name --}}
                <div class="lg:col-span-1">
                    <label class="text-xs text-gray-500">Zone Name</label>
                    <input name="name" value="{{ old('name', $rate->name) }}"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 text-sm"
                        placeholder="e.g. West Malaysia, East Malaysia, Digital Product">
                </div>

                {{-- Code --}}
                <div class="lg:col-span-1">
                    <label class="text-xs text-gray-500">Code</label>
                    <input name="code" value="{{ old('code', $rate->code) }}"
                        @if ($rate->exists) readonly class="mt-1 w-full rounded-xl border-gray-200 bg-gray-50 text-gray-500 cursor-not-allowed text-sm"
                        @else
                            class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 text-sm" @endif
                        placeholder="e.g. west_my, east_my, digital">
                    @if (!$rate->exists)
                        <p class="mt-1 text-xs text-gray-400">
                            Used in logic, keep it short and lowercase (e.g. <code>west_my</code>).
                        </p>
                    @endif
                </div>
            </div>

            {{-- Rate + Note --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 pt-3">
                {{-- Rate --}}
                <div class="lg:col-span-1">
                    <label class="text-xs text-gray-500">Rate (RM)</label>
                    <input name="rate" type="number" min="0" step="0.01"
                        value="{{ old('rate', $rate->rate ?? 0) }}"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 text-sm"
                        placeholder="e.g. 8.00">
                    <p class="mt-1 text-xs text-gray-500">
                        Flat shipping fee per order for this zone.
                    </p>
                </div>

                {{-- Info card --}}
                <div class="lg:col-span-1">
                    <div class="border rounded-xl p-4 bg-gray-50">
                        <p class="text-sm font-medium text-gray-900 mb-1">How to use</p>
                        <ul class="text-xs text-gray-500 space-y-1">
                            <li>• Example: <span class="font-semibold">West Malaysia</span> → code <code>west_my</code></li>
                            <li>• Example: <span class="font-semibold">East Malaysia</span> → code <code>east_my</code></li>
                            <li>• Example: <span class="font-semibold">Digital Product</span> → code <code>digital</code>
                            </li>
                            <li>• Your checkout logic will pick rate by matching this code.</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-5 flex justify-end gap-2">

             <button class="px-4 py-2 rounded-xl bg-[#D4AF37] text-white font-semibold hover:bg-[#c29c2f]">
                Save
            </button>

            <a href="{{ route('admin.shipping.index') }}"
                class="px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50 transition">
                Cancel
            </a>
        </div>
    </form>
@endsection
