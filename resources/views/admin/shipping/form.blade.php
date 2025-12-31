@extends('admin.layouts.app')

@section('content')
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-semibold text-gray-900 tracking-tight">
                {{ $rate->exists ? 'Edit Shipping Zone' : 'Create Shipping Zone' }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">Define geographical zones and their respective flat-rate fees.</p>
        </div>

        <a href="{{ route('admin.shipping.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200
                   text-sm font-semibold text-gray-600 hover:bg-gray-50 transition shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            <span>Back</span>
        </a>
    </div>

    <form method="POST"
        action="{{ $rate->exists ? route('admin.shipping.update', $rate) : route('admin.shipping.store') }}"
        class="bg-white border border-[#D4AF37]/18 rounded-2xl p-6 shadow-[0_18px_40px_rgba(0,0,0,0.06)] max-w-4xl">

        @csrf
        @if ($rate->exists)
            @method('PUT')
        @endif

        <div class="w-full grid grid-cols-1 lg:grid-cols-6 gap-8">

            {{-- Left: Rate Settings --}}
            <div class="lg:col-span-3 space-y-6">
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-1.5 h-6 bg-[#D4AF37] rounded-full"></span>
                    <h2 class="font-bold text-gray-900 uppercase text-xs tracking-widest">Zone Details</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs uppercase font-black tracking-widest text-gray-400">Zone Name</label>
                        <input name="name" value="{{ old('name', $rate->name) }}"
                            class="mt-1.5 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 font-medium"
                            placeholder="e.g. West Malaysia">
                    </div>

                    <div>
                        <label class="text-xs uppercase font-black tracking-widest text-gray-400">Identification
                            Code</label>
                        <input name="code" value="{{ old('code', $rate->code) }}"
                            @if ($rate->exists) readonly @endif
                            class="mt-1.5 w-full rounded-xl border-gray-200 font-mono text-sm {{ $rate->exists ? 'bg-gray-50 text-gray-400 cursor-not-allowed' : 'focus:border-[#D4AF37] focus:ring-[#D4AF37]/30' }}"
                            placeholder="e.g. west_my">
                        @if (!$rate->exists)
                            <p class="mt-1.5 text-xs text-gray-400 font-medium italic">* Use lowercase and underscores only.
                            </p>
                        @endif
                    </div>
                </div>

                <div class="pt-4">
                    <label class="text-xs uppercase font-black tracking-widest text-gray-400 block mb-1.5">Shipping Fee
                        (RM)</label>
                    <div class="relative group max-w-xs">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-400 font-bold text-sm">RM</span>
                        </div>
                        <input name="rate" type="number" min="0" step="0.01"
                            value="{{ old('rate', $rate->rate ?? 0) }}"
                            class="pl-12 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 font-bold text-lg text-gray-900"
                            placeholder="0.00">
                    </div>
                    <p class="mt-2 text-xs text-gray-500 font-medium">This is a flat-rate fee applied to the entire order.
                    </p>
                </div>
            </div>

            {{-- Right: Documentation/Info --}}
            {{-- Info card --}}
            <div class="lg:col-span-3">
                <div class="border rounded-2xl p-5 bg-gray-50 relative">

                    {{-- Accent bar --}}
                    <span class="absolute left-0 top-0 h-full w-1 rounded-l-2xl bg-[#D4AF37]/60"></span>

                    {{-- Header --}}
                    <div class="flex items-center gap-2 mb-3 text-[#8f6a10]">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0
                          11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25
                          0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75
                          0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75
                          1.75 0 009.253 9H9z" clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm font-black uppercase tracking-widest">How to Use</p>
                    </div>

                    {{-- List --}}
                    <ul class="text-xs text-gray-600 space-y-2">

                        <li>• Example: <span class="font-semibold">West Malaysia</span> → code <code>west_my</code></li>
                        <li>• Example: <span class="font-semibold">East Malaysia</span> → code <code>east_my</code></li>
                        <li>• Example: <span class="font-semibold">Digital Product</span> → code <code>digital</code></li>

                        <li class="pt-2 border-t border-gray-200/70">
                            <div class="flex items-center justify-between mb-1">
                                <span class="italic text-gray-500">Checkout Logic</span>
                                <span class="px-2 py-0.5 rounded bg-white border text-[10px] font-bold text-[#D4AF37]">
                                    TIP
                                </span>
                            </div>
                            <span class="block">
                                Your checkout logic will automatically match rates using this code.
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>

        {{-- Actions --}}
        <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-gray-50">
            <a href="{{ route('admin.shipping.index') }}"
                class="px-6 py-2.5 rounded-xl border border-gray-200 text-sm font-bold text-gray-500 hover:bg-gray-50 transition">
                Discard
            </a>
            <button
                class="px-10 py-2.5 rounded-xl bg-[#D4AF37] text-white font-bold text-sm hover:bg-[#c29c2f] transition shadow-lg shadow-[#D4AF37]/20">
                {{ $rate->exists ? 'Update Rate' : 'Create Zone' }}
            </button>
        </div>
    </form>
@endsection
