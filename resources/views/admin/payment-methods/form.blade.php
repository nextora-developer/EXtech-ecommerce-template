@extends('admin.layouts.app')

@section('content')
    <div class="flex items-start justify-between mb-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">
                {{ $paymentMethod->exists ? 'Edit Payment Method' : 'New Payment Method' }}
            </h1>
            <p class="text-sm text-gray-500">
                Configure bank transfer account or payment gateway
            </p>
        </div>

        <a href="{{ route('admin.payment-methods.index') }}"
            class="px-4 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-50 transition">
            Back
        </a>
    </div>

    <form method="POST" enctype="multipart/form-data"
        action="{{ $paymentMethod->exists
            ? route('admin.payment-methods.update', $paymentMethod)
            : route('admin.payment-methods.store') }}"
        class="bg-white rounded-2xl border border-[#D4AF37]/18 p-5 max-w-4xl shadow-[0_18px_40px_rgba(0,0,0,0.06)]">

        @csrf
        @if ($paymentMethod->exists)
            @method('PUT')
        @endif

        <div class="space-y-5">

            {{-- Name + Code --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs text-gray-500">Display Name</label>
                    <input name="name" value="{{ old('name', $paymentMethod->name) }}"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30"
                        placeholder="e.g. Online Transfer / FPX" required>
                    @error('name')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-xs text-gray-500">Code (for system)</label>
                    <input name="code" value="{{ old('code', $paymentMethod->code) }}"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30"
                        placeholder="online_transfer / toyyibpay / stripe" required>
                    @error('code')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Row 2 — Short Description --}}
            <div class="mt-3">
                <label class="text-xs text-gray-500">Short Description</label>

                <input name="short_description" value="{{ old('short_description', $paymentMethod->short_description) }}"
                    class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30"
                    placeholder="e.g. Transfer to company bank account & upload receipt">

                <p class="text-[11px] text-gray-400 mt-1">
                    This will appear under the payment title in checkout.
                </p>

                @error('short_description')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>


            {{-- Status + Default --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 pt-2">
                <div class="border rounded-xl p-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Active</p>
                        <p class="text-xs text-gray-500">Enable / disable this payment method</p>
                    </div>

                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                            @checked(old('is_active', $paymentMethod->exists ? $paymentMethod->is_active : true))>
                        <div
                            class="w-11 h-6 bg-gray-200 rounded-full peer
                                   peer-checked:bg-[#D4AF37]
                                   after:content-['']
                                   after:absolute after:top-[2px] after:left-[2px]
                                   after:bg-white after:h-5 after:w-5 after:rounded-full
                                   after:transition-all 
                                   peer-checked:after:translate-x-full">
                        </div>
                    </label>
                </div>

                <div class="border rounded-xl p-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Default</p>
                        <p class="text-xs text-gray-500">Use as primary payment method at checkout</p>
                    </div>

                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_default" value="1" class="sr-only peer"
                            @checked(old('is_default', $paymentMethod->is_default ?? false))>
                        <div
                            class="w-11 h-6 bg-gray-200 rounded-full peer
                                   peer-checked:bg-[#D4AF37]
                                   after:content-['']
                                   after:absolute after:top-[2px] after:left-[2px]
                                   after:bg-white after:h-5 after:w-5 after:rounded-full
                                   after:transition-all 
                                   peer-checked:after:translate-x-full">
                        </div>
                    </label>
                </div>
            </div>

            {{-- Bank Info --}}
            <div class="border rounded-xl p-4 space-y-4">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-[0.08em]">
                    Bank Transfer Details (for Online Transfer)
                </p>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs text-gray-500">Bank Name</label>
                        <input name="bank_name" value="{{ old('bank_name', $paymentMethod->bank_name) }}"
                            class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30"
                            placeholder="e.g. Maybank, CIMB">
                    </div>

                    <div>
                        <label class="text-xs text-gray-500">Account Name</label>
                        <input name="bank_account_name"
                            value="{{ old('bank_account_name', $paymentMethod->bank_account_name) }}"
                            class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30"
                            placeholder="Company Sdn Bhd">
                    </div>

                    <div>
                        <label class="text-xs text-gray-500">Account Number</label>
                        <input name="bank_account_number"
                            value="{{ old('bank_account_number', $paymentMethod->bank_account_number) }}"
                            class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30"
                            placeholder="1234567890">
                    </div>
                </div>

                <div>
                    <label class="text-xs text-gray-500">Instructions (shown to customer)</label>

                    <textarea name="instructions" rows="3"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 text-sm text-left"
                        placeholder="e.g. Please transfer within 24 hours and upload your receipt.">{{ old('instructions', $paymentMethod->instructions) }}</textarea>
                </div>

            </div>

            {{-- DuitNow QR Upload （参考 Banner 上传块） --}}
            <div class="border rounded-xl p-4">
                <label class="text-xs text-gray-500 block mb-2">DuitNow QR (optional)</label>

                <div class="flex items-center gap-4">
                    {{-- preview container --}}
                    <div class="h-24 w-24 rounded-lg bg-gray-100 border overflow-hidden flex items-center justify-center">

                        <img id="qrPreview"
                            src="{{ $paymentMethod->duitnow_qr_path ? asset('storage/' . $paymentMethod->duitnow_qr_path) : '' }}"
                            class="h-full w-full object-contain {{ $paymentMethod->duitnow_qr_path ? '' : 'hidden' }}"
                            alt="QR Preview">

                        <div id="qrPlaceholder" class="{{ $paymentMethod->duitnow_qr_path ? 'hidden' : '' }}">
                            <svg class="h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 16.5V6.75A2.25 2.25 0 015.25 4.5h13.5A2.25 2.25 0 0121 6.75v9.75" />
                            </svg>
                        </div>
                    </div>

                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900" id="qrFileName">
                            {{ $paymentMethod->duitnow_qr_path ? 'Current QR uploaded' : 'No image selected' }}
                        </div>

                        <div class="text-xs text-gray-500 mt-1" id="qrFileMeta">
                            {{ $paymentMethod->duitnow_qr_path ? 'You can replace it below' : 'PNG/JPG up to 4MB' }}
                        </div>

                        <div class="mt-3 flex flex-wrap items-center gap-3">

                            {{-- Upload / Replace --}}
                            <label
                                class="inline-flex items-center px-3 py-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 cursor-pointer text-sm">

                                {{ $paymentMethod->duitnow_qr_path ? 'Replace QR' : 'Choose file' }}

                                <input id="qrInput" type="file" name="duitnow_qr" class="hidden" accept="image/*">
                            </label>

                            {{-- Clear = 只清除这次选的文件，不影响现有 QR --}}
                            <button type="button" id="qrClearBtn"
                                class="px-3 py-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-sm">
                                Clear
                            </button>
                        </div>


                        @error('duitnow_qr')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5 flex gap-2">
            <button
                class="px-4 py-2 rounded-xl bg-[#D4AF37]/15 border border-[#D4AF37]/30 text-[#8f6a10]
                       hover:bg-[#D4AF37]/20 transition font-semibold">
                Save
            </button>

            <a href="{{ route('admin.payment-methods.index') }}"
                class="px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50 transition">
                Cancel
            </a>
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
                fileName.textContent = 'No image selected';
                fileMeta.textContent = 'PNG/JPG up to 4MB';
            });
        });
    </script>
@endpush
