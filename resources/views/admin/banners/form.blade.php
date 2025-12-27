@extends('admin.layouts.app')

@section('content')
    <div class="flex items-start justify-between mb-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">
                {{ $banner->exists ? 'Edit Banner' : 'New Banner' }}
            </h1>
            <p class="text-sm text-gray-500">Add homepage banner (1920 × 600 recommended)</p>
        </div>

        <a href="{{ route('admin.banners.index') }}"
            class="px-4 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-50 transition">
            Back
        </a>
    </div>

    <form method="POST" enctype="multipart/form-data"
        action="{{ $banner->exists ? route('admin.banners.update', $banner) : route('admin.banners.store') }}"
        class="bg-white rounded-2xl border border-[#D4AF37]/18 p-5 max-w-4xl shadow-[0_18px_40px_rgba(0,0,0,0.06)]">

        @csrf
        @if ($banner->exists)
            @method('PUT')
        @endif

        <div class="space-y-4">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                {{-- Banner Title --}}
                <div class="lg:col-span-1">
                    <label class="text-xs text-gray-500">Title (optional)</label>
                    <input name="title" value="{{ old('title', $banner->title) }}"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30"
                        placeholder="e.g. Raya Sale, Clearance, etc">
                </div>

                {{-- Link URL --}}
                <div class="lg:col-span-1">
                    <label class="text-xs text-gray-500">Link URL (optional)</label>
                    <input name="link_url" value="{{ old('link_url', $banner->link_url) }}"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30"
                        placeholder="https:// or /shop/category/towels">
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 pt-3">
                {{-- LEFT: Banner image upload --}}
                <div class="lg:col-span-2">

                    <div class="border rounded-xl p-4 mt-3">
                        <label class="text-xs text-gray-500 block mb-2">Banner Image (1920 × 600 recommended)</label>

                        <div class="flex items-center gap-4">

                            {{-- preview container --}}
                            <div
                                class="h-24 w-48 rounded-lg bg-gray-100 border overflow-hidden flex items-center justify-center">

                                <img id="bannerPreview"
                                    src="{{ $banner->image_path ? asset('storage/' . $banner->image_path) : '' }}"
                                    class="h-full w-full object-cover {{ $banner->image_path ? '' : 'hidden' }}"
                                    alt="Banner Preview">

                                <div id="bannerPlaceholder" class="{{ $banner->image_path ? 'hidden' : '' }}">
                                    <svg class="h-10 w-10 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 16.5V6.75A2.25 2.25 0 015.25 4.5h13.5A2.25 2.25 0 0121 6.75v9.75" />
                                    </svg>
                                </div>
                            </div>

                            <div class="flex-1">

                                <div class="text-sm font-medium text-gray-900" id="bannerFileName">
                                    {{ $banner->image_path ? 'Current banner uploaded' : 'No image selected' }}
                                </div>

                                <div class="text-xs text-gray-500 mt-1" id="bannerFileMeta">
                                    {{ $banner->image_path ? 'You can replace it below' : 'PNG/JPG up to 3MB' }}
                                </div>

                                <div class="mt-3 flex items-center gap-2">
                                    <label
                                        class="inline-flex items-center px-3 py-2 rounded-lg border border-gray-300 bg-white
                                        hover:bg-gray-50 cursor-pointer text-sm">
                                        Choose file
                                        <input id="bannerInput" type="file" name="image" class="hidden"
                                            accept="image/*">
                                    </label>

                                    <button type="button" id="bannerClearBtn"
                                        class="px-3 py-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-sm">
                                        Clear
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT: settings --}}
                <div class="space-y-4">

                    <div>
                        <label class="text-xs text-gray-500 block">Sort Order</label>
                        <input name="sort_order" value="{{ old('sort_order', $banner->sort_order ?? 0) }}"
                            class="w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30"
                            type="number" min="0">
                    </div>

                    <div class="border rounded-xl p-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Active</p>
                            <p class="text-xs text-gray-500">Visible in homepage carousel</p>
                        </div>

                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                                @checked(old('is_active', $banner->is_active ?? true))>
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
            </div>

        </div>

        <div class="mt-5 flex gap-2">
            <button
                class="px-4 py-2 rounded-xl bg-[#D4AF37]/15 border border-[#D4AF37]/30 text-[#8f6a10]
                hover:bg-[#D4AF37]/20 transition font-semibold">
                Save
            </button>

            <a href="{{ route('admin.banners.index') }}"
                class="px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50 transition">
                Cancel
            </a>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('bannerInput');
            const preview = document.getElementById('bannerPreview');
            const placeholder = document.getElementById('bannerPlaceholder');
            const fileName = document.getElementById('bannerFileName');
            const fileMeta = document.getElementById('bannerFileMeta');
            const clearBtn = document.getElementById('bannerClearBtn');

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
                fileMeta.textContent = 'PNG/JPG up to 3MB';
            });
        });
    </script>
@endpush
