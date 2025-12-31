@extends('admin.layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                {{ $category->exists ? 'Edit Category' : 'Create New Category' }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">Define your category details and aesthetic icon.</p>
        </div>

        <a href="{{ route('admin.categories.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200 text-gray-600 font-medium hover:bg-gray-50 hover:text-gray-900 transition-all shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            <span>Back to List</span>
        </a>
    </div>

    <form method="POST" enctype="multipart/form-data"
        action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
        class="max-w-4xl">
        @csrf
        @if ($category->exists)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                    <h2 class="text-sm font-bold text-gray-900 mb-5 uppercase tracking-wider">General Information</h2>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-[13px] font-bold text-gray-700 mb-2">Category Name</label>
                            <input name="name" value="{{ old('name', $category->name) }}"
                                class="w-full rounded-xl border-gray-200 bg-gray-50/50 px-4 py-3 focus:bg-white focus:border-[#D4AF37] focus:ring-4 focus:ring-[#D4AF37]/10 transition-all placeholder:text-gray-400"
                                placeholder="e.g. Premium Accessories" required>
                        </div>

                        <div>
                            <label class="block text-[13px] font-bold text-gray-700 mb-2">Custom Slug <span
                                    class="text-gray-400 font-normal">(Optional)</span></label>
                            <div class="relative">
                                <span
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 text-sm font-mono">/</span>
                                <input name="slug" value="{{ old('slug', $category->slug) }}"
                                    class="w-full rounded-xl border-gray-200 bg-gray-50/50 pl-8 pr-4 py-3 focus:bg-white focus:border-[#D4AF37] focus:ring-4 focus:ring-[#D4AF37]/10 transition-all font-mono text-sm"
                                    placeholder="auto-generated-if-empty">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                    <h2 class="text-sm font-bold text-gray-900 mb-5 uppercase tracking-wider">Upload Image</h2>

                    <div
                        class="flex flex-col md:flex-row items-center gap-8 p-4 rounded-2xl bg-gray-50/50 border border-dashed border-gray-200">
                        <div class="relative group">
                            <div
                                class="h-24 w-24 rounded-2xl bg-white border-2 border-white shadow-md overflow-hidden flex items-center justify-center ring-2 ring-gray-100 transition-transform group-hover:scale-105">
                                <img id="catIconPreview"
                                    src="{{ $category->icon ? asset('storage/' . $category->icon) : '' }}"
                                    class="h-full w-full object-cover {{ $category->icon ? '' : 'hidden' }}" />
                                <div id="catIconPlaceholder" class="{{ $category->icon ? 'hidden' : '' }}">
                                    <svg class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="flex-1 text-center md:text-left">
                            <h3 class="text-sm font-bold text-gray-900" id="catIconFileName">
                                {{ $category->icon ? 'Existing Icon' : 'Select Category Icon' }}
                            </h3>
                            <p class="text-xs text-gray-500 mt-1 mb-4" id="catIconFileMeta">
                                {{ $category->icon ? 'You can keep current or upload a new one' : 'Recommended: PNG/JPG, Square, Max 1MB' }}
                            </p>

                            <div class="flex items-center justify-center md:justify-start gap-3">
                                <label
                                    class="px-4 py-2 bg-gray-900 text-white rounded-xl text-xs font-bold cursor-pointer hover:bg-gray-800 transition-all active:scale-95 shadow-md">
                                    Upload New Image
                                    <input id="catIconInput" type="file" name="icon" class="hidden" accept="image/*">
                                </label>
                                <button type="button" id="catIconClearBtn"
                                    class="px-4 py-2 bg-white border border-gray-200 text-gray-600 rounded-xl text-xs font-bold hover:bg-red-50 hover:text-red-600 hover:border-red-100 transition-all">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                    <h2 class="text-sm font-bold text-gray-900 mb-5 uppercase tracking-wider">Publishing</h2>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-[13px] font-bold text-gray-700 mb-2">Display Order</label>
                            <div class="flex items-center gap-3">
                                <input name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}"
                                    class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 focus:bg-white focus:border-[#D4AF37] focus:ring-4 focus:ring-[#D4AF37]/10 transition-all"
                                    type="number" min="0">
                            </div>
                        </div>

                        <hr class="border-gray-50">

                        <div class="flex items-center justify-between group">
                            <div>
                                <p class="text-[13px] font-bold text-gray-900 group-hover:text-[#8f6a10] transition-colors">
                                    Visible Status</p>
                                <p class="text-[11px] text-gray-500">Show on store front</p>
                            </div>

                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                                    @checked(old('is_active', $category->is_active ?? true))>
                                <div
                                    class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-emerald-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:h-5 after:w-5 after:rounded-full after:transition-all peer-checked:after:translate-x-full">
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <button
                        class="w-full py-4 bg-[#D4AF37] text-white font-bold rounded-2xl shadow-lg shadow-[#D4AF37]/20 hover:bg-[#c29c2f] hover:shadow-xl transition-all active:scale-[0.98]">
                        Save Category
                    </button>
                    <a href="{{ route('admin.categories.index') }}"
                        class="w-full py-4 bg-white border border-gray-200 text-gray-500 font-bold rounded-2xl text-center hover:bg-gray-50 transition-all">
                        Discard Changes
                    </a>
                </div>
            </div>
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
                const sizes = ['B', 'KB', 'MB'];
                const i = Math.floor(Math.log(bytes) / Math.log(1024));
                return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
            };

            input.addEventListener('change', () => {
                const file = input.files && input.files[0];
                if (!file) return;

                fileName.textContent = file.name;
                fileMeta.textContent =
                    `${formatBytes(file.size)} • ${file.type.split('/')[1].toUpperCase()}`;

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
                fileName.textContent = 'Select Category Icon';
                fileMeta.textContent = 'Recommended: PNG/JPG, Square, Max 1MB';
            });
        });
    </script>
@endpush
