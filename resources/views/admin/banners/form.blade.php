@extends('admin.layouts.app')

@section('content')
    {{-- Header Section --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-semibold text-gray-900 tracking-tight">
                {{ $banner->exists ? 'Edir Banner' : 'Create Homepage Banner' }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">Recommended aspect ratio: 1920 × 600 pixels.</p>
        </div>

        <a href="{{ route('admin.banners.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200
                   text-sm font-semibold text-gray-600 hover:bg-gray-50 transition shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            <span>Back</span>
        </a>
    </div>

    <form method="POST" enctype="multipart/form-data"
        action="{{ $banner->exists ? route('admin.banners.update', $banner) : route('admin.banners.store') }}"
        class="bg-white border border-[#D4AF37]/18 rounded-2xl p-6 shadow-[0_18px_40px_rgba(0,0,0,0.06)] max-w-5xl">

        @csrf
        @if ($banner->exists)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Left Side: Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-1.5 h-6 bg-[#D4AF37] rounded-full"></span>
                        <h2 class="font-bold text-gray-900 uppercase text-xs tracking-widest">Banner Content</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs uppercase font-black tracking-widest text-gray-400">Display Title</label>
                            <input name="title" value="{{ old('title', $banner->title) }}"
                                class="mt-1.5 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 font-medium"
                                placeholder="e.g. Raya Sale 2024">
                        </div>

                        <div>
                            <label class="text-xs uppercase font-black tracking-widest text-gray-400">Destination URL</label>
                            <input name="link_url" value="{{ old('link_url', $banner->link_url) }}"
                                class="mt-1.5 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 font-medium"
                                placeholder="e.g. /shop/towels">
                        </div>
                    </div>
                </div>

                {{-- Image Upload Zone --}}
                <div class="border border-dashed border-gray-200 rounded-2xl p-6 bg-gray-50/50">
                    <label class="text-xs uppercase font-black tracking-widest text-[#8f6a10] block mb-4">Creative Asset</label>

                    <div class="flex flex-col md:flex-row gap-6">
                        {{-- Preview Box --}}
                        <div class="w-full md:w-64 aspect-[19/6] md:aspect-auto md:h-32 rounded-xl bg-white border border-gray-200 shadow-inner flex items-center justify-center overflow-hidden">
                            <img id="bannerPreview"
                                src="{{ $banner->image_path ? asset('storage/' . $banner->image_path) : '' }}"
                                class="h-full w-full object-cover {{ $banner->image_path ? '' : 'hidden' }}"
                                alt="Preview">

                            <div id="bannerPlaceholder" class="flex flex-col items-center gap-1 {{ $banner->image_path ? 'hidden' : '' }}">
                                <svg class="h-8 w-8 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6.75a1.5 1.5 0 00-1.5-1.5H3.75a1.5 1.5 0 00-1.5 1.5v12.75a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                                <span class="text-[10px] font-bold text-gray-300 uppercase tracking-tighter">No Image</span>
                            </div>
                        </div>

                        {{-- Upload Controls --}}
                        <div class="flex-1 flex flex-col justify-center">
                            <div class="text-sm font-bold text-gray-900 leading-none" id="bannerFileName">
                                {{ $banner->image_path ? 'Existing Banner' : 'Upload Graphic' }}
                            </div>
                            <p class="text-xs text-gray-400 mt-1 uppercase font-semibold" id="bannerFileMeta">
                                {{ $banner->image_path ? 'Replace by selecting a new file' : 'PNG, JPG or WebP (Max 3MB)' }}
                            </p>

                            <div class="mt-4 flex gap-2">
                                <label class="px-4 py-2 rounded-lg bg-white border border-gray-300 text-xs font-bold text-gray-600 hover:bg-gray-50 cursor-pointer shadow-sm transition">
                                    Select Image
                                    <input id="bannerInput" type="file" name="image" class="hidden" accept="image/*">
                                </label>
                                <button type="button" id="bannerClearBtn"
                                    class="px-4 py-2 rounded-lg border border-red-100 text-xs font-bold text-red-400 hover:bg-red-50 transition">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Side: Visibility & Status --}}
            <div class="space-y-6">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-1.5 h-6 bg-[#D4AF37] rounded-full"></span>
                        <h2 class="font-bold text-gray-900 uppercase text-xs tracking-widest">Configuration</h2>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="text-xs uppercase font-black tracking-widest text-gray-400">Display Priority</label>
                            <input name="sort_order" value="{{ old('sort_order', $banner->sort_order ?? 0) }}"
                                class="mt-1.5 w-full rounded-xl border-gray-200 focus:border-[#D4AF37] focus:ring-[#D4AF37]/30 font-medium"
                                type="number" min="0">
                            <p class="text-xs text-gray-400 mt-1">Lower numbers appear first.</p>
                        </div>

                        <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-black uppercase tracking-widest text-gray-700">Banner Status</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Show on live store</p>
                                </div>

                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                                        @checked(old('is_active', $banner->is_active ?? true))>
                                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#D4AF37]
                                                after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                                after:bg-white after:h-5 after:w-5 after:rounded-full
                                                after:transition-all peer-checked:after:translate-x-full">
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full mb-3 px-6 py-3 rounded-xl bg-[#D4AF37] text-white font-bold text-sm hover:bg-[#c29c2f] transition shadow-lg shadow-[#D4AF37]/20">
                        {{ $banner->exists ? 'Update Changes' : 'Publish Banner' }}
                    </button>
                    <a href="{{ route('admin.banners.index') }}"
                        class="block text-center px-6 py-3 rounded-xl border border-gray-200 text-sm font-bold text-gray-500 hover:bg-gray-50 transition">
                        Discard
                    </a>
                </div>
            </div>
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
                fileName.textContent = 'Upload Graphic';
                fileMeta.textContent = 'PNG, JPG or WebP (Max 3MB)';
            });
        });
    </script>
@endpush