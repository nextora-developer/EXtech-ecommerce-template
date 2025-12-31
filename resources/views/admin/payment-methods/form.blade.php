@extends('admin.layouts.app')

@section('content')
    {{-- Header Section --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-semibold text-gray-900 tracking-tight">
                {{ $paymentMethod->exists ? 'Configure Payment' : 'New Payment Method' }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">Set up bank transfers, QRs, or gateway integrations.</p>
        </div>

        <a href="{{ route('admin.payment-methods.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200
                   text-sm font-semibold text-gray-600 hover:bg-gray-50 transition shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            <span>Back</span>
        </a>
    </div>

    <form method="POST" enctype="multipart/form-data"
        action="{{ $paymentMethod->exists ? route('admin.payment-methods.update', $paymentMethod) : route('admin.payment-methods.store') }}"
        class="max-w-5xl space-y-6">

        @csrf
        @if ($paymentMethod->exists)
            @method('PUT')
        @endif

        {{-- Section 1: Core Configuration --}}
        <div class="bg-white border border-[#D4AF37]/18 rounded-2xl p-6 shadow-[0_18px_40px_rgba(0,0,0,0.06)]">
            <div class="flex items-center gap-2 mb-6">
                <span class="w-1.5 h-6 bg-[#D4AF37] rounded-full"></span>
                <h2 class="font-bold text-gray-900 uppercase text-xs tracking-widest">General Settings</h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="text-xs uppercase font-black tracking-widest text-gray-400">Display Name</label>
                    <input name="name" value="{{ old('name', $paymentMethod->name) }}"
                        class="mt-1.5 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 font-medium"
                        placeholder="e.g. Bank Transfer / FPX" required>
                    @error('name')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-xs uppercase font-black tracking-widest text-gray-400">System Code</label>
                    <input name="code" value="{{ old('code', $paymentMethod->code) }}"
                        class="mt-1.5 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 font-mono text-sm"
                        placeholder="online_transfer" required>
                    @error('code')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="lg:col-span-2">
                    <label class="text-xs uppercase font-black tracking-widest text-gray-400">Short Description</label>
                    <input name="short_description"
                        value="{{ old('short_description', $paymentMethod->short_description) }}"
                        class="mt-1.5 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 font-medium"
                        placeholder="e.g. Instant transfer to our Maybank account">
                    <p class="text-xs text-gray-400 mt-1 uppercase font-bold">Appears below the title at
                        checkout</p>
                </div>
            </div>

            {{-- Visibility Toggles --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8 pt-6 border-t border-gray-50">
                <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-gray-700">Active Status</p>
                        <p class="text-xs text-gray-400 mt-0.5 font-bold">Show as option for customers</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                            @checked(old('is_active', $paymentMethod->exists ? $paymentMethod->is_active : true))>
                        <div
                            class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#D4AF37] after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:h-5 after:w-5 after:rounded-full after:transition-all peer-checked:after:translate-x-full">
                        </div>
                    </label>
                </div>

                <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-gray-700">Set Default</p>
                        <p class="text-xs text-gray-400 mt-0.5 font-bold">Pre-selected at checkout</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_default" value="1" class="sr-only peer"
                            @checked(old('is_default', $paymentMethod->is_default ?? false))>
                        <div
                            class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#D4AF37] after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:h-5 after:w-5 after:rounded-full after:transition-all peer-checked:after:translate-x-full">
                        </div>
                    </label>
                </div>
            </div>
        </div>

        {{-- Section 2: Bank Details --}}
        <div class="bg-white border border-[#D4AF37]/18 rounded-2xl p-6 shadow-[0_18px_40px_rgba(0,0,0,0.06)]">
            <div class="flex items-center gap-2 mb-6">
                <span class="w-1.5 h-6 bg-[#D4AF37] rounded-full"></span>
                <h2 class="font-bold text-gray-900 uppercase text-xs tracking-widest">Bank & Instructions</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="text-xs uppercase font-black tracking-widest text-gray-400">Bank Name</label>
                    <input name="bank_name" value="{{ old('bank_name', $paymentMethod->bank_name) }}"
                        class="mt-1.5 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 font-medium"
                        placeholder="e.g. Maybank / CIMB">
                </div>
                <div>
                    <label class="text-xs uppercase font-black tracking-widest text-gray-400">Account Name</label>
                    <input name="bank_account_name"
                        value="{{ old('bank_account_name', $paymentMethod->bank_account_name) }}"
                        class="mt-1.5 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 font-medium"
                        placeholder="Company Sdn Bhd">
                </div>
                <div>
                    <label class="text-xs uppercase font-black tracking-widest text-gray-400">Account Number</label>
                    <input name="bank_account_number"
                        value="{{ old('bank_account_number', $paymentMethod->bank_account_number) }}"
                        class="mt-1.5 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 font-medium"
                        placeholder="1234567890">
                </div>
            </div>

            <div>
                <label class="text-xs uppercase font-black tracking-widest text-gray-400">Customer Instructions</label>
                <textarea name="instructions" rows="3"
                    class="mt-1.5 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 text-sm"
                    placeholder="e.g. Please upload your bank slip within 24 hours.">{{ old('instructions', $paymentMethod->instructions) }}</textarea>
            </div>
        </div>

        {{-- Section 3: QR Asset --}}
        <div class="bg-white border border-[#D4AF37]/18 rounded-2xl p-6 shadow-[0_18px_40px_rgba(0,0,0,0.06)]">
            <div class="flex items-center gap-2 mb-6">
                <span class="w-1.5 h-6 bg-[#D4AF37] rounded-full"></span>
                <h2 class="font-bold text-gray-900 uppercase text-xs tracking-widest">DuitNow QR Graphic</h2>
            </div>

            <div class="flex flex-col md:flex-row gap-6 items-center">
                <div
                    class="w-32 h-32 rounded-2xl bg-gray-50 border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden shrink-0 shadow-inner">
                    <img id="qrPreview"
                        src="{{ $paymentMethod->duitnow_qr_path ? asset('storage/' . $paymentMethod->duitnow_qr_path) : '' }}"
                        class="h-full w-full object-contain p-2 {{ $paymentMethod->duitnow_qr_path ? '' : 'hidden' }}">

                    <div id="qrPlaceholder"
                        class="flex flex-col items-center gap-1 {{ $paymentMethod->duitnow_qr_path ? 'hidden' : '' }}">
                        <svg class="h-6 w-6 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                </div>

                <div class="flex-1">
                    <p class="text-sm font-bold text-gray-900" id="qrFileName">
                        {{ $paymentMethod->duitnow_qr_path ? 'Current QR Code Active' : 'No QR uploaded' }}
                    </p>
                    <p class="text-xs text-gray-400 mt-0.5 uppercase font-bold" id="qrFileMeta">
                        Recommended: Square PNG/JPG, Max 4MB
                    </p>

                    <div class="mt-4 flex gap-2">
                        <label
                            class="px-4 py-2 rounded-lg bg-white border border-gray-300 text-[11px] font-black uppercase tracking-wider text-gray-600 hover:bg-gray-50 cursor-pointer shadow-sm transition">
                            Choose Image
                            <input id="qrInput" type="file" name="duitnow_qr" class="hidden" accept="image/*">
                        </label>
                        <button type="button" id="qrClearBtn"
                            class="px-4 py-2 rounded-lg border border-red-100 text-[11px] font-black uppercase tracking-wider text-red-400 hover:bg-red-50 transition">
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('admin.payment-methods.index') }}"
                class="px-6 py-3 rounded-xl border border-gray-200 text-sm font-bold text-gray-500 hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit"
                class="px-10 py-3 rounded-xl bg-[#D4AF37] text-white font-bold text-sm hover:bg-[#c29c2f] transition shadow-lg shadow-[#D4AF37]/20">
                Save Method
            </button>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('qrInput');
            const preview = document.getElementById('qrPreview');
            const placeholder = document.getElementById('qrPlaceholder');
            const fileName = document.getElementById('qrFileName');
            const fileMeta = document.getElementById('qrFileMeta');
            const clearBtn = document.getElementById('qrClearBtn');

            if (!input) return;

            const formatBytes = (bytes) => {
                if (!bytes) return '';
                const sizes = ['B', 'KB', 'MB'];
                const i = Math.floor(Math.log(bytes) / Math.log(1024));
                return (bytes / Math.pow(1024, i)).toFixed(i === 0 ? 0 : 1) + ' ' + sizes[i];
            };

            input.addEventListener('change', () => {
                const file = input.files && input.files[0];
                if (!file) return;

                fileName.textContent = file.name;
                fileMeta.textContent = `${formatBytes(file.size)} • ${file.type || 'image'}`;

                const url = URL.createObjectURL(file);
                preview.src = url;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            });

            clearBtn.addEventListener('click', () => {
                input.value = '';
                preview.src = '';
                preview.classList.add('hidden');
                placeholder.classList.remove('hidden');
                fileName.textContent = 'No QR uploaded';
                fileMeta.textContent = 'Recommended: Square PNG/JPG, Max 4MB';
            });
        });
    </script>
@endpush
