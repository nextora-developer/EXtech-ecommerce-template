@extends('admin.layouts.app')

@section('content')
    <div class="flex items-start justify-between mb-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">
                {{ $category->exists ? 'Edit Category' : 'New Category' }}
            </h1>
            <p class="text-sm text-gray-500">Keep it simple: name + optional slug.</p>
        </div>
        <a href="{{ route('admin.categories.index') }}"
            class="px-4 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-50 transition">
            Back
        </a>
    </div>

    <form method="POST" enctype="multipart/form-data"
        action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
        class="bg-white rounded-2xl border border-[#D4AF37]/18 p-5 max-w-3xl shadow-[0_18px_40px_rgba(0,0,0,0.06)]">
        @csrf
        @if ($category->exists)
            @method('PUT')
        @endif

        <div class="space-y-4">
            <div>
                <label class="text-xs text-gray-500">Name</label>
                <input name="name" value="{{ old('name', $category->name) }}"
                    class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30"
                    placeholder="e.g. Accessories" required>
            </div>

            <div>
                <label class="text-xs text-gray-500">Slug (optional)</label>
                <input name="slug" value="{{ old('slug', $category->slug) }}"
                    class="mt-1 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30"
                    placeholder="auto-generated if empty">
            </div>

            {{-- Icon upload card (with preview + filename + clear) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                {{-- LEFT: Icon upload (span 2) --}}
                <div class="lg:col-span-2">
                    {{-- 这里放你现有的 Icon upload card（带 JS 的那块） --}}
                    {{-- Icon upload card (with preview + filename + clear) --}}
                    <div class="border rounded-xl p-4 mt-3">
                        <label class="text-xs text-gray-500 block mb-2">Category Icon</label>

                        <div class="flex items-center gap-4">
                            <div
                                class="h-16 w-16 rounded-full bg-gray-100 border overflow-hidden flex items-center justify-center">
                                <img id="catIconPreview"
                                    src="{{ $category->icon ? asset('storage/' . $category->icon) : '' }}"
                                    class="h-full w-full object-cover {{ $category->icon ? '' : 'hidden' }}"
                                    alt="Icon Preview" />
                                <div id="catIconPlaceholder" class="{{ $category->icon ? 'hidden' : '' }}">
                                    <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 16.5V6.75A2.25 2.25 0 015.25 4.5h13.5A2.25 2.25 0 0121 6.75v9.75" />
                                    </svg>
                                </div>
                            </div>

                            <div class="flex-1">
                                <div class="text-sm font-medium text-gray-900" id="catIconFileName">
                                    {{ $category->icon ? 'Current image uploaded' : 'No image selected' }}
                                </div>
                                <div class="text-xs text-gray-500 mt-1" id="catIconFileMeta">
                                    {{ $category->icon ? 'You can replace it below' : 'PNG/JPG up to 1MB' }}
                                </div>

                                <div class="mt-3 flex items-center gap-2">
                                    <label
                                        class="inline-flex items-center px-3 py-2 rounded-lg border border-gray-300 bg-white
                                      hover:bg-gray-50 cursor-pointer text-sm">
                                        Choose file
                                        <input id="catIconInput" type="file" name="icon" class="hidden"
                                            accept="image/*">
                                    </label>

                                    <button type="button" id="catIconClearBtn"
                                        class="px-3 py-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-sm">
                                        Clear
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT: Settings --}}
                <div class="space-y-4">

                    {{-- Sort Order card --}}
                    <label class="text-xs text-gray-500 block">Sort Order</label>
                    <input name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}"
                        class="w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30"
                        type="number" min="0">

                    {{-- Active card (toggle) --}}
                    <div class="border rounded-xl p-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Active</p>
                            <p class="text-xs text-gray-500">Visible for product selection</p>
                        </div>

                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                                @checked(old('is_active', $category->is_active ?? true))>
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
            <a href="{{ route('admin.categories.index') }}"
                class="px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50 transition">
                Cancel
            </a>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('catIconInput');
            const preview = document.getElementById('catIconPreview');
            const placeholder = document.getElementById('catIconPlaceholder');
            const fileName = document.getElementById('catIconFileName');
            const fileMeta = document.getElementById('catIconFileMeta');
            const clearBtn = document.getElementById('catIconClearBtn');

            if (!input) return;

            const formatBytes = (bytes) => {
                if (!bytes) return '';
                const sizes = ['B', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(1024));
                return (bytes / Math.pow(1024, i)).toFixed(i === 0 ? 0 : 1) + ' ' + sizes[i];
            };

            input.addEventListener('change', () => {
                const file = input.files && input.files[0];
                if (!file) return;

                fileName.textContent = file.name; // ✅ show real selected file name
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
                fileMeta.textContent = 'PNG/JPG up to 1MB';
            });
        });
    </script>
@endpush
